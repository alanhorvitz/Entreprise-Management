<div>
    <div class="modal-header mb-4">
        <h2 class="card-title text-xl flex items-center gap-2">
            <span class="iconify w-6 h-6 text-primary" data-icon="solar:pen-new-square-bold-duotone"></span> Edit Task
        </h2>
    </div>
    
    <form wire:submit.prevent="update">
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
                    <select id="project_id" class="select select-bordered w-full" wire:model="project_id" required>
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
            
            <div class="form-control mt-4">
                <label class="label" for="description">
                    <span class="label-text">Description</span>
                </label>
                <textarea id="description" class="textarea textarea-bordered h-24" wire:model="description"></textarea>
                @error('description') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-control mt-4">
                <label class="label" for="assignees">
                    <span class="label-text">Assign To</span>
                </label>
                <select id="assignees" class="select select-bordered w-full" wire:model="assignees" multiple>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                <div class="text-xs text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple users</div>
                @error('assignees') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-control mt-4">
                <label class="label" for="status">
                    <span class="label-text">Approval Status</span>
                </label>
                <select id="status" class="select select-bordered w-full" wire:model="status">
                    <option value="pending_approval">Pending Approval</option>
                    <option value="approved">Approved</option>
                </select>
                @error('status') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        </div>
        
        <div class="modal-footer mt-6 flex justify-end gap-2">
            <button type="button" class="btn" wire:click="$dispatch('closeModal')">
                <span class="iconify w-5 h-5 mr-2" data-icon="solar:close-circle-bold-duotone"></span>
                Cancel
            </button>
            <button type="submit" class="btn btn-primary">
                <span class="iconify w-5 h-5 mr-2" data-icon="solar:disk-bold-duotone"></span>
                Update Task
            </button>
        </div>
    </form>
</div> 