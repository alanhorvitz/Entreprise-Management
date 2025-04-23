<div>
    <form wire:submit.prevent="create" id="create-project-form" class="space-y-6 max-w-5xl mx-auto">
        <!-- Basic Information Section -->
        <div class="card bg-base-100 shadow-xl form-section">
            <div class="card-body">
                <h2 class="card-title text-xl flex items-center gap-2">
                    <span class="iconify lucide--info"></span> Basic Information
                </h2>
                <p class="text-sm text-base-content/70 mb-4">Enter the essential details about your project</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text required-field">Project Name</span>
                        </label>
                        <input type="text" wire:model="name" placeholder="Enter project name" class="input input-bordered w-full" required />
                        <label class="label">
                            <span class="label-text-alt">Choose a clear, descriptive name</span>
                        </label>
                        @error('name') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text required-field">Department</span>
                        </label>
                        <select wire:model.live="departmentId" class="select select-bordered w-full" required>
                            <option value="">Select department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id') <span class="text-error text-sm">{{ $message }}</span> @enderror
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
                            <span class="label-text required-field">End Date</span>
                        </label>
                        <input type="date" wire:model="end_date" class="input input-bordered w-full" required />
                        @error('end_date') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text required-field">Status</span>
                        </label>
                        <select wire:model.live="status" class="select select-bordered w-full" required>
                            <option value="">Select status</option>
                            <option value="planning">Planning</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="on_hold">On Hold</option>
                        </select>
                        @error('status') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="form-control w-full mt-4">
                    <label class="label">
                        <span class="label-text required-field">Project Description</span>
                    </label>
                    <textarea wire:model="description" class="textarea textarea-bordered h-32 w-full" placeholder="Provide a detailed description of the project objectives, scope, and expected outcomes" required></textarea>
                    @error('description') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        
        <!-- Team & Resources Section -->
        <div class="card bg-base-100 shadow-xl form-section">
            <div class="card-body">
                <h2 class="card-title text-xl flex items-center gap-2">
                    <span class="iconify lucide--users"></span> Team & Resources
                </h2>
                <p class="text-sm text-base-content/70 mb-4">Assign project members and allocate resources</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text required-field">Project Supervisor</span>
                        </label>
                        <select wire:model.live="project_manager_id" class="select select-bordered w-full" required>
                            <option value="">Select project supervisor</option>
                            @foreach($supervisors as $supervisor)
                                <option value="{{ $supervisor->id }}">{{ $supervisor->first_name }} {{ $supervisor->last_name }}</option>
                            @endforeach
                        </select>
                        @error('project_manager_id') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text required-field">Team Manager</span>
                        </label>
                        <select wire:model.live="team_manager_id" class="select select-bordered w-full" required>
                            <option value="">Select team manager</option>
                            @if($availableTeamManagers && $availableTeamManagers->count() > 0)
                                @foreach($availableTeamManagers as $manager)
                                    <option value="{{ $manager->id }}">{{ $manager->first_name }} {{ $manager->last_name }}</option>
                                @endforeach
                            @else
                                <option disabled>{{ empty($selectedTeamMembers) ? 'Select team members first' : 'Loading available managers...' }}</option>
                            @endif
                        </select>
                        @error('team_manager_id') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="form-control w-full">
                    <div class="form-control mt-4 w-full">
                        <label class="label">
                            <span class="label-text">Budget</span>
                        </label>
                        <div class="join w-full">
                            <input type="number" wire:model="budget" placeholder="0.00" min="0" step="0.01" class="input input-bordered join-item w-full" />
                            <span class="join-item flex items-center px-3 bg-base-200 border border-r-0 border-base-300">DH</span>
                        </div>
                        @error('budget') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="form-control w-full mt-4">
                    <label class="label">
                        <span class="label-text">Team Members</span>
                    </label>
                    <div class="bg-base-200 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                            @if($departmentMembers)
                                @foreach($departmentMembers as $member)
                                    <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-base-300 rounded-md">
                                        <input type="checkbox" wire:model.live="selectedTeamMembers" value="{{ $member->id }}" class="checkbox checkbox-sm" />
                                        <div class="flex items-center gap-2">
                                            <div class="avatar placeholder">
                                                <div class="bg-neutral text-neutral-content w-8 rounded-full">
                                                    <span>{{ substr($member->first_name, 0, 1) }}{{ substr($member->last_name, 0, 1) }}</span>
                                                </div>
                                            </div>
                                            <span>{{ $member->first_name }} {{ $member->last_name }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    @error('selectedTeamMembers') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        
        <!-- Additional Settings Section -->
        <div class="card bg-base-100 shadow-xl form-section">
            <div class="card-body">
                <h2 class="card-title text-xl flex items-center gap-2">
                    <span class="iconify lucide--cog"></span> Additional Settings
                </h2>
                <p class="text-sm text-base-content/70 mb-4">Configure additional project settings</p>
                <div class="form-control w-full mt-4">
                    <label class="label cursor-pointer justify-start gap-2">
                        <input type="checkbox" wire:model="send_notifications" class="checkbox checkbox-primary" />
                        <span class="label-text">Send notifications to team members</span>
                    </label>
                </div>
                
                <div class="form-control w-full">
                    <label class="label cursor-pointer justify-start gap-2">
                        <input type="checkbox" wire:model="is_featured" class="checkbox checkbox-primary" />
                        <span class="label-text">Add to featured projects</span>
                    </label>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="flex justify-end gap-4 sticky bottom-0 bg-base-200 p-4 shadow-lg rounded-t-lg">
            <a href="{{ route('projects.index') }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Project</button>
        </div>
    </form>
</div>
