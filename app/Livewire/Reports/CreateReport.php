<?php

namespace App\Livewire\Reports;

use App\Models\DailyReport;
use App\Models\Project;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use App\Mail\ReportCreatedMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Notification;
use App\Models\ProjectMember;

class CreateReport extends Component
{
    public $date;
    public $summary;
    public $project_id;
    public $availableProjects = [];

    protected $rules = [
        'date' => 'required|date|date_equals:today',
        'summary' => 'required|string',
        'project_id' => 'required|exists:projects,id'
    ];

    public function mount()
    {
        $user = auth()->user();
        $employeeId = $user->employee->id;
        
        // Check if user is director or team leader
        $isTeamLeader = ProjectMember::where('employee_id', $employeeId)
            ->where('role', 'team_leader')
            ->exists();

        if (!$user->hasRole('director') && !$isTeamLeader) {
            abort(403, 'Unauthorized action.');
        }

        $this->date = now()->format('Y-m-d');
        $this->loadAvailableProjects();
    }

    public function loadAvailableProjects()
    {
        $this->availableProjects = Project::whereHas('members', function($query) {
            $query->where('user_id', auth()->id());
        })->get();
    }

    public function save()
    {
        $user = auth()->user();
        $employeeId = $user->employee->id;
        
        // Check if user is director or team leader
        $isTeamLeader = ProjectMember::where('employee_id', $employeeId)
            ->where('role', 'team_leader')
            ->exists();

        if (!$user->hasRole('director') && !$isTeamLeader) {
            abort(403, 'Unauthorized action.');
        }

        $this->validate();

        // Ensure the date is today
        if (!Carbon::parse($this->date)->isToday()) {
            $this->addError('date', 'Reports can only be submitted for today.');
            return;
        }

        // Check if a report already exists for this date (regardless of project)
        $existingReport = DailyReport::where('user_id', auth()->id())
            ->whereDate('date', $this->date)
            ->first();

        if ($existingReport) {
            $this->addError('date', 'You have already submitted a report for today. Only one report per day is allowed.');
            return;
        }

        try {
            $report = DailyReport::create([
                'user_id' => auth()->id(),
                'project_id' => $this->project_id,
                'date' => $this->date,
                'summary' => $this->summary,
                'submitted_at' => now()
            ]);

            // Get the project and its supervisor
            $project = Project::with('supervisedBy')->find($this->project_id);
            
            // Send notification to supervisor if exists
            if ($project && $project->supervisedBy) {
                Notification::create([
                    'user_id' => $project->supervisedBy->id,
                    'from_id' => auth()->id(),
                    'title' => 'New Daily Report Submitted',
                    'message' => auth()->user()->name . ' has submitted a daily report for project: ' . $project->name,
                    'type' => 'reminder',
                    'data' => [
                        'report_id' => $report->id,
                        'project_id' => $project->id,
                        'project_name' => $project->name,
                        'submitted_by' => auth()->user()->name
                    ],
                    'is_read' => false
                ]);
            }

            Mail::to('kniptodati@gmail.com')->send(new ReportCreatedMail($report));
            


            $this->dispatch('notify', [
                'message' => 'Report created successfully!',
                'type' => 'success',
            ]);
            return redirect()->route('reports.index');
        } catch (QueryException $e) {
            $this->dispatch('notify', [
                'message' => 'Unable to create report. Please try again.',
                'type' => 'error',
            ]);
            return;
        }
    }

    public function render()
    {
        $user = auth()->user();
        $employeeId = $user->employee->id;
        
        // Check if user is director or team leader
        $isTeamLeader = ProjectMember::where('employee_id', $employeeId)
            ->where('role', 'team_leader')
            ->exists();

        if (!$user->hasRole('director') && !$isTeamLeader) {
            abort(403, 'Unauthorized action.');
        }

        return view('livewire.reports.create-report');
    }
}