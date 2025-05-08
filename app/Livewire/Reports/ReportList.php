<?php

namespace App\Livewire\Reports;

use App\Models\Project;
use App\Models\DailyReport;
use App\Models\Department;
use App\Models\ProjectMember;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReportList extends Component
{
    use WithPagination;

    public $dateRange = 'today';
    public $projectFilter = '';
    public $departmentFilter = '';
    public $searchAssignee = '';
    public $showAssigneeModal = false;
    public $selectedAssigneeId = null;
    public $showEditModal = false;
    public $selectedReportId = null;
    public $showDeleteModal = false;
    public $reportToDeleteId = null;
    public $startDate;
    public $endDate;

    protected $queryString = [
        'dateRange' => ['except' => 'today'],
        'projectFilter' => ['except' => ''],
        'departmentFilter' => ['except' => ''],
        'searchAssignee' => ['except' => ''],
    ];

    protected $listeners = [
        'closeAssigneeModal' => 'closeAssigneeModal',
        'closeEditModal' => 'closeEditModal',
        'closeDeleteModal' => 'closeDeleteModal',
        'reportUpdated' => '$refresh',
        'reportDeleted' => '$refresh'
    ];

    public function mount()
    {
        $this->setDateRange('today');
    }

    protected function canModifyReport($report)
    {
        $user = Auth::user();
        
        // Directors can modify any report
        if ($user->hasRole('director')) {
            return true;
        }
        
        // Supervisors can only view reports from their supervised projects
        if ($user->hasRole('supervisor')) {
            return false;
        }

        // Team leaders can modify their own reports
        $isTeamLeader = ProjectMember::where('project_id', $report->project_id)
            ->whereHas('employee', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('role', 'team_leader')
            ->exists();

        if ($isTeamLeader) {
            return true;
        }

        // Regular employees cannot modify any reports
        return false;
    }

    public function deleteReport($reportId)
    {
        $report = DailyReport::find($reportId);
        
        if (!$report) {
            $this->dispatch('notify', [
                'message' => 'Report not found.',
                'type' => 'error',
            ]);
            return;
        }

        if (!$this->canModifyReport($report)) {
            $this->dispatch('notify', [
                'message' => 'You are not authorized to delete this report.',
                'type' => 'error',
            ]);
            return;
        }
        
        $report->delete();
        
        $this->dispatch('notify', [
            'message' => 'Report deleted successfully!',
            'type' => 'success',
        ]);
    }

    public function setDateRange($range)
    {
        $this->dateRange = $range;
        $today = Carbon::today();

        switch ($range) {
            case 'today':
                $this->startDate = $today;
                $this->endDate = $today;
                break;
            case 'yesterday':
                $this->startDate = $today->subDay();
                $this->endDate = $this->startDate;
                break;
            case 'last_7_days':
                $this->startDate = $today->subDays(6);
                $this->endDate = $today;
                break;
            case 'last_30_days':
                $this->startDate = $today->subDays(29);
                $this->endDate = $today;
                break;
        }

        $this->resetPage();
    }

    public function showAssigneeReport($userId)
    {
        $this->selectedAssigneeId = $userId;
        $this->showAssigneeModal = true;
    }

    public function closeAssigneeModal()
    {
        $this->showAssigneeModal = false;
        $this->selectedAssigneeId = null;
    }

    public function showEditReport($reportId)
    {
        $report = DailyReport::find($reportId);
        
        if (!$report) {
            $this->dispatch('notify', [
                'message' => 'Report not found.',
                'type' => 'error',
            ]);
            return;
        }

        if (!$this->canModifyReport($report)) {
            $this->dispatch('notify', [
                'message' => 'You are not authorized to edit this report.',
                'type' => 'error',
            ]);
            return;
        }

        $this->selectedReportId = $reportId;
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->selectedReportId = null;
    }

    public function showDeleteReport($reportId)
    {
        $report = DailyReport::find($reportId);
        
        if (!$report) {
            $this->dispatch('notify', [
                'message' => 'Report not found.',
                'type' => 'error',
            ]);
            return;
        }

        if (!$this->canModifyReport($report)) {
            $this->dispatch('notify', [
                'message' => 'You are not authorized to delete this report.',
                'type' => 'error',
            ]);
            return;
        }

        $this->reportToDeleteId = $reportId;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->reportToDeleteId = null;
    }

    public function updatingSearchAssignee()
    {
        $this->resetPage();
    }

    public function updatingProjectFilter()
    {
        $this->resetPage();
    }

    public function updatingDepartmentFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        // First, get the reports with proper eager loading
        $reportsQuery = DailyReport::with(['user', 'user.employee.departments', 'project']);

        $user = auth()->user();
        $employee = $user->employee;

        // Filter reports based on user's role
        if ($user->hasRole('director')) {
            // Directors can see all reports
            // No additional filtering needed
        } elseif ($user->hasRole('supervisor')) {
            // Supervisors can see reports from projects they supervise
            $reportsQuery->whereHas('project', function($query) use ($user) {
                $query->where('supervised_by', $user->id);
            });
        } else {
            // For team_leader and employee
            if ($employee) {
                // Get user's projects where they are either team leader or member
                $userProjects = Project::whereHas('members', function($query) use ($employee) {
                    $query->where('employee_id', $employee->id);
                })->pluck('id')->toArray();

                // Filter reports to only show those from user's projects
                $reportsQuery->whereIn('project_id', $userProjects);
            } else {
                // If no employee record exists, return empty paginated collection
                return view('livewire.reports.report-list', [
                    'reports' => DailyReport::where('id', 0)->paginate(10),
                    'projects' => collect(),
                    'departments' => Department::all(),
                    'dateRangeOptions' => [
                        'today' => 'Today (' . Carbon::today()->format('M d, Y') . ')',
                        'yesterday' => 'Yesterday',
                        'last_7_days' => 'Last 7 Days',
                        'last_30_days' => 'Last 30 Days'
                    ]
                ]);
            }
        }

        $reportsQuery->when($this->startDate && $this->endDate, function($query) {
                $query->whereBetween('date', [$this->startDate, $this->endDate]);
            })
            ->when($this->projectFilter, function($query) {
                $query->where('project_id', $this->projectFilter);
            })
            ->when($this->departmentFilter, function($query) {
                $query->whereHas('user.employee.departments', function($q) {
                    $q->where('departments.id', $this->departmentFilter);
                });
            })
            ->when($this->searchAssignee, function($query) {
                $query->whereHas('user', function($q) {
                    $q->where(function($sq) {
                        $sq->where('first_name', 'like', '%' . $this->searchAssignee . '%')
                          ->orWhere('last_name', 'like', '%' . $this->searchAssignee . '%');
                    });
                });
            })
            ->orderBy('date', 'desc');

        // Get available projects for filter based on user role
        if ($user->hasRole('director')) {
            $projects = Project::all();
        } elseif ($user->hasRole('supervisor')) {
            $projects = Project::where('supervised_by', $user->id)->get();
        } else {
            $projects = $employee ? Project::whereHas('members', function($query) use ($employee) {
                $query->where('employee_id', $employee->id);
            })->get() : collect();
        }

        return view('livewire.reports.report-list', [
            'reports' => $reportsQuery->paginate(10),
            'projects' => $projects,
            'departments' => Department::all(),
            'dateRangeOptions' => [
                'today' => 'Today (' . Carbon::today()->format('M d, Y') . ')',
                'yesterday' => 'Yesterday',
                'last_7_days' => 'Last 7 Days',
                'last_30_days' => 'Last 30 Days'
            ]
        ]);
    }
}