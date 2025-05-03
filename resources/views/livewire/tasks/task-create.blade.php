<div class="space-y-6">
    <div class="modal-header border-b border-base-300 pb-4">
        <h2 class="text-2xl font-bold flex items-center gap-2 text-primary">
            <span class="iconify w-7 h-7" data-icon="solar:add-square-bold-duotone"></span> Create New Task
        </h2>
    </div>
    
    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Basic Task Info -->
        <div class="bg-base-200/50 p-4 rounded-lg shadow-sm">
            <div class="form-control mb-4">
                    <label class="label" for="title">
                    <span class="label-text font-medium">Task Title</span>
                    </label>
                <input type="text" id="title" class="input input-bordered w-full focus:input-primary text-lg" wire:model="title" required />
                    @error('title') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                
            <div class="form-control">
                <label class="label" for="description">
                    <span class="label-text font-medium">Description</span>
                </label>
                <textarea id="description" class="textarea textarea-bordered h-36 focus:textarea-primary text-base" wire:model="description" placeholder="Enter a detailed description of the task..."></textarea>
                <div class="mt-1 text-xs text-base-content/70">
                    Use this space to provide a clear and detailed description of what needs to be accomplished.
                </div>
                @error('description') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        </div>
        
        <!-- Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="form-control">
                    <label class="label" for="project_id">
                    <span class="label-text font-medium">Project</span>
                    </label>
                <select id="project_id" class="select select-bordered w-full focus:select-primary" wire:model="project_id" required>
                        <option value="">Select a project</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                    @error('project_id') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            
            <div class="form-control">
                <label class="label" for="current_status">
                    <span class="label-text font-medium">Status</span>
                </label>
                <select id="current_status" class="select select-bordered w-full focus:select-primary" wire:model="current_status">
                    <option value="todo">Not Started</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
                @error('current_status') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
            </div>
                
                <div class="form-control">
                    <label class="label" for="priority">
                    <span class="label-text font-medium">Priority</span>
                    </label>
                <select id="priority" class="select select-bordered w-full focus:select-primary" wire:model="priority">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                    @error('priority') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-control">
                    <label class="label" for="start_date">
                    <span class="label-text font-medium">Start Date</span>
                    </label>
                <input type="date" id="start_date" class="input input-bordered w-full focus:input-primary" wire:model="start_date" />
                    @error('start_date') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-control">
                    <label class="label" for="due_date">
                    <span class="label-text font-medium">Due Date</span>
                    </label>
                <input type="date" id="due_date" class="input input-bordered w-full focus:input-primary" wire:model="due_date" />
                    @error('due_date') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
            
        <!-- Assignment Section - Made Bigger and More Prominent -->
        <div class="bg-base-200/50 p-5 rounded-lg shadow-sm border-l-4 border-primary">
            <h3 class="text-lg font-medium mb-3 flex items-center">
                <span class="iconify w-5 h-5 mr-2 text-primary" data-icon="solar:users-group-rounded-bold-duotone"></span>
                Assign Team Members
            </h3>
            
            <div class="form-control">
                <select id="assignees" class="select select-bordered w-full h-auto min-h-16 focus:select-primary" wire:model="assignees" multiple>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                <div class="mt-2 text-sm flex items-center text-base-content/70">
                    <span class="iconify w-4 h-4 mr-1" data-icon="solar:info-circle-bold-duotone"></span>
                    Hold Ctrl/Cmd to select multiple team members
                </div>
                @error('assignees') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        </div>
            
        <!-- Repetitive Task Section -->
        <div class="bg-base-200/50 p-4 rounded-lg shadow-sm">
            <div class="form-control mb-2">
                <label class="label cursor-pointer justify-start gap-2">
                    <input type="checkbox" class="checkbox checkbox-primary" wire:model="is_repetitive" />
                    <span class="label-text font-medium">Make this a repetitive task</span>
                </label>
                @error('is_repetitive') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            
            @if($is_repetitive)
            <div class="bg-base-100 p-4 rounded-lg mt-2 border border-base-300">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label" for="repetition_rate">
                            <span class="label-text font-medium">Repeat</span>
                        </label>
                        <select id="repetition_rate" class="select select-bordered w-full focus:select-primary" wire:model="repetition_rate">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                        @error('repetition_rate') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-control">
                        <label class="label" for="recurrence_end_date">
                            <span class="label-text font-medium">Until (optional)</span>
                        </label>
                        <input type="date" id="recurrence_end_date" class="input input-bordered w-full focus:input-primary" wire:model="recurrence_end_date" />
                        @error('recurrence_end_date') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                @if($repetition_rate === 'weekly')
                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text font-medium">Repeat on</span>
                    </label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($weekdays as $value => $day)
                            <label class="label cursor-pointer gap-2 bg-base-100 px-3 py-2 rounded-md border border-base-300 hover:bg-base-200 transition-colors">
                                <input type="checkbox" class="checkbox checkbox-sm checkbox-primary" 
                                    value="{{ $value }}" 
                                    wire:model="recurrence_days" />
                                <span class="label-text">{{ $day }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('recurrence_days') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                @endif
                
                @if($repetition_rate === 'monthly')
                <div class="form-control mt-4">
                    <label class="label" for="recurrence_month_day">
                        <span class="label-text font-medium">Day of month</span>
                    </label>
                    <select id="recurrence_month_day" class="select select-bordered w-full focus:select-primary" wire:model="recurrence_month_day">
                        @for($i = 1; $i <= 31; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                    @error('recurrence_month_day') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                @endif
            </div>
            @endif
        </div>
        
        <div class="border-t border-base-300 pt-4 flex justify-end gap-3">
            <button type="button" class="btn btn-outline" wire:click="$dispatch('closeModal')">
                <span class="iconify w-5 h-5 mr-2" data-icon="solar:close-circle-bold-duotone"></span>
                Cancel
            </button>
            <button type="submit" class="btn btn-primary">
                <span class="iconify w-5 h-5 mr-2" data-icon="solar:disk-bold-duotone"></span>
                Create Task
            </button>
        </div>
    </form>
</div> 