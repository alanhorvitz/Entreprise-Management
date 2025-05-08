<?php

namespace App\Livewire\Employees;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Status;
use App\Models\Type;
use App\Models\Operator;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeeList extends Component
{
    use WithPagination;

    public $search = '';
    public $departmentFilter = '';
    public $statusFilter = '';
    public $typeFilter = '';
    public $operatorFilter = '';
    public $isProjectFilter = '';
    public $isAnapecFilter = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'departmentFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'typeFilter' => ['except' => ''],
        'operatorFilter' => ['except' => ''],
        'isProjectFilter' => ['except' => ''],
        'isAnapecFilter' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openViewModal($employeeId)
    {
        $params = [
            'component' => 'employees.employee-details',
            'arguments' => [
                'employeeId' => $employeeId
            ]
        ];
        $this->dispatch('openModal', $params);
    }

    public function render()
    {
        $employees = Employee::query()
            ->with(['user', 'departments', 'status', 'operator', 'types'])
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhere('employee_code', 'like', '%' . $this->search . '%')
                ->orWhere('cin', 'like', '%' . $this->search . '%')
                ->orWhere('professional_email', 'like', '%' . $this->search . '%');
            })
            ->when($this->departmentFilter, function ($query) {
                $query->whereHas('departments', function ($q) {
                    $q->where('departments.id', $this->departmentFilter);
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status_id', $this->statusFilter);
            })
            ->when($this->typeFilter, function ($query) {
                $query->whereHas('types', function ($q) {
                    $q->where('types.id', $this->typeFilter);
                });
            })
            ->when($this->operatorFilter, function ($query) {
                $query->where('operator_id', $this->operatorFilter);
            })
            ->when($this->isProjectFilter !== '', function ($query) {
                $query->where('is_project', $this->isProjectFilter === '1');
            })
            ->when($this->isAnapecFilter !== '', function ($query) {
                $query->where('is_anapec', $this->isAnapecFilter === '1');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.employees.employee-list', [
            'employees' => $employees,
            'departments' => Department::all(),
            'statuses' => Status::all(),
            'types' => Type::all(),
            'operators' => Operator::all(),
        ]);
    }
} 