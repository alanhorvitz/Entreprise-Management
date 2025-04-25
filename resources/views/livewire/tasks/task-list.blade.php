<div>
    <!-- Filters Section -->
    <div class="card bg-base-100 shadow-md mb-6">
        <div class="card-body">
            <div class="flex justify-between items-center mb-5">
                <h2 class="card-title">Tasks</h2>
                
                <button wire:click="openCreateModal()" class="btn btn-primary">
                    <span class="iconify w-5 h-5 mr-2" data-icon="solar:add-circle-bold-duotone"></span> New Task
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Project</span>
                    </label>
                    <select class="select select-bordered w-full" wire:model.live="projectFilter">
                        <option value="">All Projects</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Priority</span>
                    </label>
                    <select class="select select-bordered w-full" wire:model.live="priorityFilter">
                        <option value="">All Priorities</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Status</span>
                    </label>
                    <select class="select select-bordered w-full" wire:model.live="statusFilter">
                        <option value="">All Statuses</option>
                        <option value="todo">Not Started</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Assignee</span>
                    </label>
                    <select class="select select-bordered w-full" wire:model.live="assigneeFilter">
                        <option value="">All Assignees</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Repetitive</span>
                    </label>
                    <select class="select select-bordered w-full" wire:model.live="repetitiveFilter">
                        <option value="">All Tasks</option>
                        <option value="yes">Repetitive Only</option>
                        <option value="no">Non-repetitive Only</option>
                    </select>
                </div>
            </div>
            
            <div class="flex justify-between mt-4">
                <div class="form-control max-w-xs">
                    <div class="join">
                        <input class="input input-bordered join-item w-full" placeholder="Search tasks..." wire:model.live="search" />
                        <button class="btn join-item">
                            <span class="iconify w-5 h-5" data-icon="solar:magnifer-bold-duotone"></span>
                        </button>
                    </div>
                </div>
                
                <div class="flex gap-2">
                    <button class="btn btn-outline btn-sm" wire:click="clearFilters">
                        <span class="iconify w-5 h-5 mr-1" data-icon="solar:close-circle-bold-duotone"></span> Clear Filters
                    </button>
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-outline btn-sm">
                            <span class="iconify w-5 h-5 mr-1" data-icon="solar:sort-from-bottom-to-top-bold-duotone"></span> Sort By
                        </div>
                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                            <li><a wire:click="sortBy('due_date')" class="cursor-pointer {{ $sortField === 'due_date' && $sortDirection === 'asc' ? 'active' : '' }}">Due Date (Earliest)</a></li>
                            <li><a wire:click="sortBy('due_date')" class="cursor-pointer {{ $sortField === 'due_date' && $sortDirection === 'desc' ? 'active' : '' }}">Due Date (Latest)</a></li>
                            <li><a wire:click="sortBy('priority')" class="cursor-pointer {{ $sortField === 'priority' && $sortDirection === 'desc' ? 'active' : '' }}">Priority (Highest)</a></li>
                            <li><a wire:click="sortBy('priority')" class="cursor-pointer {{ $sortField === 'priority' && $sortDirection === 'asc' ? 'active' : '' }}">Priority (Lowest)</a></li>
                            <li><a wire:click="sortBy('title')" class="cursor-pointer {{ $sortField === 'title' && $sortDirection === 'asc' ? 'active' : '' }}">Name (A-Z)</a></li>
                            <li><a wire:click="sortBy('title')" class="cursor-pointer {{ $sortField === 'title' && $sortDirection === 'desc' ? 'active' : '' }}">Name (Z-A)</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tasks Table -->
    <div class="overflow-x-auto bg-base-100 rounded-lg shadow-md">
        <table class="table table-zebra w-full">
            <thead>
                <tr>
                    <th class="w-8">
                        <label>
                            <input type="checkbox" class="checkbox checkbox-sm" />
                        </label>
                    </th>
                    <th>Task</th>
                    <th>Project</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Due Date</th>
                    <th>Assignee</th>
                    <th class="w-20">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tasks as $task)
                    <tr class="hover">
                        <td>
                            <label>
                                <input type="checkbox" class="checkbox checkbox-sm" />
                            </label>
                        </td>
                        <td>
                            <div>
                                <div class="font-bold hover:text-primary cursor-pointer" wire:click="openViewModal({{ $task->id }})">
                                    {{ $task->title }}
                                    @if($task->is_repetitive)
                                        <span class="badge badge-sm badge-accent ml-1">
                                            <iconify-icon icon="lucide:repeat" class="mr-1"></iconify-icon> Recurring
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs text-base-content/70 max-w-xs truncate">{{ $task->description }}</div>
                            </div>
                        </td>
                        <td>
                            <span>{{ $task->project->name ?? 'N/A' }}</span>
                        </td>
                        <td>
                            @php
                                $statusClass = [
                                    'todo' => 'badge-secondary',
                                    'in_progress' => 'badge-primary',
                                    'completed' => 'badge-success',
                                ][$task->current_status] ?? 'badge-ghost';
                                
                                $statusLabel = [
                                    'todo' => 'To Do',
                                    'in_progress' => 'In Progress',
                                    'completed' => 'Done',
                                ][$task->current_status] ?? $task->current_status;
                            @endphp
                            <span class="badge badge-sm whitespace-nowrap !px-2 min-w-[60px] text-center {{ $statusClass }}">{{ $statusLabel }}</span>
                        </td>
                        <td>
                            @php
                                $priorityClass = [
                                    'high' => 'badge-error',
                                    'medium' => 'badge-warning',
                                    'low' => 'badge-info',
                                ][$task->priority] ?? 'badge-ghost';
                            @endphp
                            <span class="badge badge-sm whitespace-nowrap !px-2 min-w-[60px] text-center {{ $priorityClass }}">{{ ucfirst($task->priority) }}</span>
                        </td>
                        <td>{{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }}</td>
                        <td>
                            @if($task->assignedUsers->count() > 0)
                                <div class="flex items-center gap-2">
                                    <div class="avatar-group -space-x-6">
                                        @foreach($task->assignedUsers->take(3) as $user)
                                            <div class="avatar">
                                                <div class="w-8 rounded-full">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" alt="{{ $user->name }}" />
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                        @if($task->assignedUsers->count() > 3)
                                            <div class="avatar placeholder">
                                                <div class="w-8 rounded-full bg-neutral-focus text-neutral-content">
                                                    <span>+{{ $task->assignedUsers->count() - 3 }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <span class="text-sm text-gray-500">Unassigned</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown dropdown-end">
                                <div tabindex="0" role="button" class="btn btn-ghost btn-xs">
                                    <span class="iconify w-5 h-5" data-icon="solar:menu-dots-bold-duotone"></span>
                                </div>
                                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                                    <li><a wire:click="openViewModal({{ $task->id }})">
                                        <span class="iconify w-5 h-5 mr-2" data-icon="solar:eye-bold-duotone"></span> View Details
                                    </a></li>
                                    <li><a wire:click="openEditModal({{ $task->id }})">
                                        <span class="iconify w-5 h-5 mr-2" data-icon="solar:pen-bold-duotone"></span> Edit Task
                                    </a></li>
                                    <li><a wire:click="deleteTask({{ $task->id }})" class="text-error">
                                        <span class="iconify w-5 h-5 mr-2" data-icon="solar:trash-bin-trash-bold-duotone"></span> Delete Task
                                    </a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <div class="w-16 h-16 bg-neutral text-neutral-content rounded-full inline-flex items-center justify-center">
                                    <span class="iconify w-8 h-8" data-icon="solar:clipboard-list-bold-duotone"></span>
                                </div>
                                <p class="text-gray-500">No tasks found</p>
                                <button wire:click="openCreateModal()" class="btn btn-sm btn-primary">
                                    <span class="iconify w-5 h-5 mr-2" data-icon="solar:add-circle-bold-duotone"></span> Create a task
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($tasks->hasPages())
        <div class="pt-4">
            {{ $tasks->links() }}
        </div>
    @endif
</div> 