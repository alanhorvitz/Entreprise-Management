<?php

namespace App\Livewire\Reports;

use App\Models\Project;
use App\Models\DailyReport;
use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class ReportList extends Component
{
    use WithPagination;

    public $dateRange = 'today';
    public $projectFilter = '';
    public $departmentFilter = '';
    public $searchAssignee = '';
    public $startDate;
    public $endDate;
    public $showDateRangePicker = false;
    public $showAssigneeModal = false;
    public $selectedAssigneeId = null;

    protected $queryString = [
        'dateRange' => ['except' => 'today'],
        'projectFilter' => ['except' => ''],
        'departmentFilter' => ['except' => ''],
        'searchAssignee' => ['except' => ''],
    ];

    public function mount()
    {
        $this->setDateRange('today');
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
            case 'this_month':
                $this->startDate = $today->startOfMonth();
                $this->endDate = $today->endOfMonth();
                break;
            case 'custom':
                $this->showDateRangePicker = true;
                return;
        }

        $this->showDateRangePicker = false;
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
        $reportsQuery = DailyReport::with(['user', 'user.departments', 'reportTasks.task'])
            ->when($this->startDate && $this->endDate, function($query) {
                $query->whereBetween('date', [$this->startDate, $this->endDate]);
            })
            ->when($this->projectFilter, function($query) {
                $query->whereHas('reportTasks.task', function($q) {
                    $q->where('project_id', $this->projectFilter);
                });
            })
            ->when($this->departmentFilter, function($query) {
                $query->whereHas('user.departments', function($q) {
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

        $reports = $reportsQuery->paginate(10);

        return view('livewire.reports.report-list', [
            'reports' => $reports,
            'projects' => Project::all(),
            'departments' => Department::all(),
            'dateRangeOptions' => [
                'today' => 'Today (' . Carbon::today()->format('M d, Y') . ')',
                'yesterday' => 'Yesterday',
                'last_7_days' => 'Last 7 Days',
                'last_30_days' => 'Last 30 Days',
                'this_month' => 'This Month',
                'custom' => 'Custom Range'
            ]
        ]);
    }
}
