<div>
    <style>
        /* Required field styling */
        .required-field::after {
            content: '*';
            color: hsl(var(--er));
            margin-left: 0.25rem;
        }
        
        /* Form section styling */
        .form-section {
            transition: all 0.3s ease-in-out;
        }
        
        .form-section:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
    
    <form wire:submit.prevent="create" id="create-task-form" class="space-y-6 max-w-5xl mx-auto">
        <!-- Basic Information Section -->
        <div class="card bg-base-100 shadow-xl form-section">
            <div class="card-body">
                <h2 class="card-title text-xl flex items-center gap-2">
                    <span class="iconify w-6 h-6 text-primary" data-icon="solar:info-circle-bold-duotone"></span> Task Information
                </h2>
                <p class="text-sm text-base-content/70 mb-4">Enter the essential details about your task</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text required-field">Task Title</span>
                        </label>
                        <input type="text" wire:model="title" placeholder="Enter task title" class="input input-bordered w-full" required />
                        <label class="label">
                            <span class="label-text-alt">Choose a clear, descriptive title</span>
                        </label>
                        @error('title') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text required-field">Project</span>
                        </label>
                        <select wire:model="project_id" class="select select-bordered w-full" required>
                            <option value="">Select project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                        @error('project_id') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text required-field">Start Date</span>
                        </label>
                        <input type="date" wire:model="start_date" class="input input-bordered w-full" required />
                        @error('start_date') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">Due Date</span>
                        </label>
                        <input type="date" wire:model="due_date" class="input input-bordered w-full" />
                        @error('due_date') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text required-field">Priority</span>
                        </label>
                        <select wire:model="priority" class="select select-bordered w-full" required>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                        @error('priority') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text required-field">Status</span>
                        </label>
                        <select wire:model="current_status" class="select select-bordered w-full" required>
                            <option value="todo">To Do</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                        @error('current_status') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="form-control w-full mt-4">
                    <label class="label">
                        <span class="label-text required-field">Task Description</span>
                    </label>
                    <textarea wire:model="description" class="textarea textarea-bordered h-32 w-full" placeholder="Provide a detailed description of the task objectives, requirements and expectations"></textarea>
                    @error('description') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        
        <!-- Team Section -->
        <div class="card bg-base-100 shadow-xl form-section">
            <div class="card-body">
                <h2 class="card-title text-xl flex items-center gap-2">
                    <span class="iconify w-6 h-6 text-primary" data-icon="solar:users-group-rounded-bold-duotone"></span> Task Assignment
                </h2>
                <p class="text-sm text-base-content/70 mb-4">Assign team members to this task</p>
                
                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text">Assign To</span>
                    </label>
                    <select wire:model="assignees" class="select select-bordered w-full" multiple>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <div class="text-xs text-base-content/70 mt-1">Hold Ctrl/Cmd to select multiple users</div>
                    @error('assignees') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        
        <!-- Recurrence Settings -->
        <div class="card bg-base-100 shadow-xl form-section">
            <div class="card-body">
                <h2 class="card-title text-xl flex items-center gap-2">
                    <span class="iconify w-6 h-6 text-primary" data-icon="solar:repeat-bold-duotone"></span> Recurrence Settings
                </h2>
                <p class="text-sm text-base-content/70 mb-4">Configure if this task should repeat on a schedule</p>
                
                <div class="form-control">
                    <label class="label cursor-pointer justify-start gap-2">
                        <input type="checkbox" wire:model="is_repetitive" class="checkbox checkbox-primary" />
                        <span class="label-text">This is a recurring task</span>
                    </label>
                </div>
                
                @if($is_repetitive)
                <div class="form-control w-full mt-2">
                    <label class="label">
                        <span class="label-text">Repetition Schedule</span>
                    </label>
                    <select wire:model="repetition_rate" class="select select-bordered w-full">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                    @error('repetition_rate') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>
                @endif
            </div>
        </div>
        
        <!-- Reminders Settings -->
        <div class="card bg-base-100 shadow-xl form-section">
            <div class="card-body">
                <h2 class="card-title text-xl flex items-center gap-2">
                    <span class="iconify w-6 h-6 text-primary" data-icon="solar:bell-bold-duotone"></span> Reminders
                </h2>
                <p class="text-sm text-base-content/70 mb-4">Configure reminders for this task</p>
                
                <div class="form-control">
                    <label class="label cursor-pointer justify-start gap-2">
                        <input type="checkbox" wire:model="reminders_enabled" class="checkbox checkbox-primary" />
                        <span class="label-text">Enable reminders for this task</span>
                    </label>
                </div>
                
                @if($reminders_enabled)
                <div class="form-control w-full mt-2">
                    <label class="label">
                        <span class="label-text">Days Before Due Date</span>
                    </label>
                    <input type="number" wire:model="reminder_days_before" min="1" max="30" class="input input-bordered w-full" />
                    <label class="label">
                        <span class="label-text-alt">Reminder will be sent this many days before the task is due</span>
                    </label>
                    @error('reminder_days_before') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>
                @endif
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="flex justify-end gap-4 sticky bottom-0 bg-base-200 p-4 shadow-lg rounded-t-lg">
            <a href="{{ route('tasks.index') }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Task</button>
        </div>
    </form>
</div> 