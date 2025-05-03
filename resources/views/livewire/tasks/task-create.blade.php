<div>
    <div class="modal-header">
        <h3 class="text-lg font-bold">Create New Task</h3>
    </div>
    
    <form wire:submit.prevent="save">
        <div class="modal-body">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label" for="title">
                        <span class="label-text">Task Title</span>
                    </label>
                    <input type="text" id="title" class="input input-bordered w-full" wire:model="title" required />
                    @error('title') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-control">
                    <label class="label" for="project_id">
                        <span class="label-text">Project</span>
                    </label>
                    <select id="project_id" class="select select-bordered w-full" wire:model.live="project_id" required>
                        <option value="">Select a project</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                    @error('project_id') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-control">
                    <label class="label" for="priority">
                        <span class="label-text">Priority</span>
                    </label>
                    <select id="priority" class="select select-bordered w-full" wire:model="priority">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                    @error('priority') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-control">
                    <label class="label" for="current_status">
                        <span class="label-text">Status</span>
                    </label>
                    <select id="current_status" class="select select-bordered w-full" wire:model="current_status">
                        <option value="todo">Not Started</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                    @error('current_status') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-control">
                    <label class="label" for="start_date">
                        <span class="label-text">Start Date</span>
                    </label>
                    <input type="date" id="start_date" class="input input-bordered w-full" wire:model="start_date" />
                    @error('start_date') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-control">
                    <label class="label" for="due_date">
                        <span class="label-text">Due Date</span>
                    </label>
                    <input type="date" id="due_date" class="input input-bordered w-full" wire:model="due_date" />
                    @error('due_date') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div class="form-control mt-4 flex flex-col gap-2">
                <label class="label" for="description">
                    <span class="label-text">Description</span>
                </label>
                <textarea id="description" class="textarea textarea-bordered h-24 w-full" wire:model="description"></textarea>
                @error('description') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            
            <!-- Repetitive Task Section -->
            <div class="form-control mt-4">
                <label class="label cursor-pointer justify-start gap-2">
                    <input type="checkbox" class="checkbox checkbox-primary" wire:model="is_repetitive" />
                    <span class="label-text font-medium">Make this a repetitive task</span>
                </label>
                @error('is_repetitive') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            
            @if($is_repetitive)
            <div class="bg-base-200 p-4 rounded-lg mt-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label" for="repetition_rate">
                            <span class="label-text">Repeat</span>
                        </label>
                        <select id="repetition_rate" class="select select-bordered w-full" wire:model="repetition_rate">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                        @error('repetition_rate') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-control">
                        <label class="label" for="recurrence_end_date">
                            <span class="label-text">Until (optional)</span>
                        </label>
                        <input type="date" id="recurrence_end_date" class="input input-bordered w-full" wire:model="recurrence_end_date" />
                        @error('recurrence_end_date') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                @if($repetition_rate === 'weekly')
                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text">Repeat on</span>
                    </label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($weekdays as $value => $day)
                            <label class="label cursor-pointer gap-2 bg-base-100 px-3 py-2 rounded-md">
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
                        <span class="label-text">Day of month</span>
                    </label>
                    <select id="recurrence_month_day" class="select select-bordered w-full" wire:model="recurrence_month_day">
                        @for($i = 1; $i <= 31; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                    @error('recurrence_month_day') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                @endif
            </div>
            @endif
            
            <div class="form-control mt-4">
                <label class="label">
                    <span class="label-text">Assign To</span>
                </label>
                <div class="bg-base-200 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                        @if($projectMembers && count($projectMembers) > 0)
                            @foreach($projectMembers as $member)
                                <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-base-300 rounded-md">
                                    <input type="checkbox" wire:model.live="assignees" value="{{ $member->id }}" class="checkbox checkbox-sm" />
                                    <div class="flex items-center gap-2">
                                        <div class="avatar placeholder">
                                            <div class="bg-neutral text-neutral-content w-8 rounded-full">
                                                <span>{{ substr($member->first_name ?? '', 0, 1) }}{{ substr($member->last_name ?? '', 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <span>{{ $member->first_name }} {{ $member->last_name }}</span>
                                            <span class="text-sm text-gray-500 block">{{ $member->role }}</span>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        @elseif($project_id)
                            <div class="col-span-full text-center py-4 text-gray-500">
                                No team members found for this project
                            </div>
                        @else
                            <div class="col-span-full text-center py-4 text-gray-500">
                                Please select a project to see available team members
                            </div>
                        @endif
                    </div>
                </div>
                @error('assignees') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>
        </div>
        
        <div class="modal-footer mt-6 flex justify-end gap-2">
            <button type="button" class="btn" wire:click="$dispatch('closeModal')">Cancel</button>
            <button type="submit" class="btn btn-primary">Create Task</button>
        </div>
    </form>
</div> 