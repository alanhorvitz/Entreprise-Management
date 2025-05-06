<?php

namespace App\Livewire\Reports;

use App\Models\DailyReport;
use App\Models\Project;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

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
        if (!auth()->user()->can('generate reports')) {
            abort(403, 'Unauthorized action.');
        }

        $this->date = now()->format('Y-m-d H:i:s');
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
        if (!auth()->user()->can('generate reports')) {
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
            DailyReport::create([
                'user_id' => auth()->id(),
                'project_id' => $this->project_id,
                'date' => $this->date,
                'summary' => $this->summary,
                'submitted_at' => now()
            ]);

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
        if (!auth()->user()->can('generate reports')) {
            abort(403, 'Unauthorized action.');
        }

        return view('livewire.reports.create-report');
    }
}