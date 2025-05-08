<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <h2 class="text-2xl font-semibold">Employees</h2>
        <div class="join">
            <div class="join-item">
                <input wire:model.live="search" type="text" placeholder="Search employees..." class="input input-bordered w-full md:w-64" />
            </div>
            <select wire:model.live="perPage" class="select select-bordered join-item">
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
                <option value="100">100 per page</option>
            </select>
        </div>
    </div>

    <!-- Filters -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <select wire:model.live="departmentFilter" class="select select-bordered w-full">
            <option value="">All Departments</option>
            @foreach($departments as $department)
                <option value="{{ $department->id }}">{{ $department->name }}</option>
            @endforeach
        </select>

        <select wire:model.live="statusFilter" class="select select-bordered w-full">
            <option value="">All Statuses</option>
            @foreach($statuses as $status)
                <option value="{{ $status->id }}">{{ $status->status }}</option>
            @endforeach
        </select>

        <select wire:model.live="typeFilter" class="select select-bordered w-full">
            <option value="">All Types</option>
            @foreach($types as $type)
                <option value="{{ $type->id }}">{{ $type->type }}</option>
            @endforeach
        </select>

        <select wire:model.live="operatorFilter" class="select select-bordered w-full">
            <option value="">All Operators</option>
            @foreach($operators as $operator)
                <option value="{{ $operator->id }}">{{ $operator->operator }}</option>
            @endforeach
        </select>

        <select wire:model.live="isProjectFilter" class="select select-bordered w-full">
            <option value="">All Project Status</option>
            <option value="1">Project Employee</option>
            <option value="0">Non-Project Employee</option>
        </select>

        <select wire:model.live="isAnapecFilter" class="select select-bordered w-full">
            <option value="">All ANAPEC Status</option>
            <option value="1">ANAPEC Employee</option>
            <option value="0">Non-ANAPEC Employee</option>
        </select>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-base-100 rounded-lg shadow">
        <table class="table table-zebra w-full">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Code</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Contact</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                    <tr>
                        <td>
                            <div class="flex items-center space-x-3">
                                <div class="avatar">
                                    <div class="mask mask-squircle w-12 h-12">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($employee->user->first_name . ' ' . $employee->user->last_name) }}&background=random" alt="{{ $employee->user->first_name }} {{ $employee->user->last_name }}" />
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">{{ $employee->user->first_name }} {{ $employee->user->last_name }}</div>
                                    <div class="text-sm opacity-50">{{ $employee->cin }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $employee->employee_code }}</td>
                        <td>
                            <div class="flex flex-wrap gap-1">
                                @foreach($employee->departments as $department)
                                    <div class="badge badge-primary">{{ $department->name }}</div>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <div class="badge {{ $employee->status->status === 'active' ? 'badge-success' : 'badge-warning' }}">
                                {{ $employee->status->status }}
                            </div>
                        </td>
                        <td>
                            <div class="text-sm">
                                <div>{{ $employee->professional_email }}</div>
                                <div>{{ $employee->professional_num }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="flex flex-wrap gap-1">
                                @foreach($employee->types as $type)
                                    @if($type->pivot->out_date == null)
                                        <div class="badge badge-ghost">{{ $type->type }}</div>
                                    @endif
                                @endforeach

                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <button wire:click="openViewModal({{ $employee->id }})" class="btn btn-ghost btn-sm">
                                    <span class="iconify w-5 h-5" data-icon="solar:eye-bold-duotone"></span>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-gray-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>
                                <p class="text-gray-500">No employees found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div>
        {{ $employees->links() }}
    </div>

    <!-- Modal Manager -->
    <livewire:modals.modal-manager />
</div> 