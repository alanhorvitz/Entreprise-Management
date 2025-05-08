<?php

namespace App\Livewire\Employees;

use App\Models\Employee;
use Livewire\Component;

class EmployeeDetails extends Component
{
    public $employee;
    public $employeeId;

    public function mount($employeeId)
    {
        $this->employeeId = $employeeId;
        $this->loadEmployee();
    }

    public function loadEmployee()
    {
        $this->employee = Employee::with([
            'user',
            'departments',
            'status',
            'operator',
            'types' => function($query) {
                $query->withPivot('in_date', 'out_date');
            }
        ])->findOrFail($this->employeeId);
    }

    public function render()
    {
        return view('livewire.employees.employee-details');
    }
} 