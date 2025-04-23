<div>
    <!-- Filters Section -->
    <div class="card bg-base-100 shadow-xl mb-6">
        <div class="card-body">
            <div class="flex justify-between items-center mb-5">
                <h2 class="card-title">Projects</h2>
                
                <a href="{{ route('projects.create') }}" class="btn btn-primary">
                    <iconify-icon icon="lucide:plus" class="mr-2"></iconify-icon> New Project
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <div class="join w-full">
                        <input type="text" wire:model.live="search" class="input input-bordered join-item w-full" placeholder="Search projects...">
                        <button class="btn join-item">
                            <iconify-icon icon="lucide:search"></iconify-icon>
                        </button>
                    </div>
                </div>
                
                <div class="form-control">
                    <select wire:model.live="statusFilter" class="select select-bordered w-full">
                        <option value="">All Status</option>
                        <option value="planning">Planning</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="on_hold">On Hold</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($projects as $project)
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <div class="flex justify-between items-start">
                        <div class="avatar placeholder">
                            <div class="bg-neutral text-neutral-content rounded-lg w-12">
                                <span class="text-xl">{{ strtoupper(substr($project->name, 0, 2)) }}</span>
                            </div>
                        </div>
                        <div class="dropdown dropdown-end">
                            <div tabindex="0" role="button" class="btn btn-ghost btn-sm btn-circle">
                                <iconify-icon icon="lucide:more-vertical"></iconify-icon>
                            </div>
                            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                                <li><a href="{{ route('projects.edit', $project) }}">
                                    <iconify-icon icon="lucide:edit"></iconify-icon> Edit
                                </a></li>
                                <li><a class="text-error">
                                    <iconify-icon icon="lucide:trash-2"></iconify-icon> Delete
                                </a></li>
                            </ul>
                        </div>
                    </div>

                    <h2 class="card-title mt-4">{{ $project->name }}</h2>
                    <p class="text-base-content/70 line-clamp-2">{{ $project->description }}</p>

                    <div class="flex flex-col gap-2 mt-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-base-content/70">Status</span>
                            <span class="badge {{ 
                                $project->status === 'completed' ? 'badge-success' : 
                                ($project->status === 'in_progress' ? 'badge-primary' : 
                                ($project->status === 'on_hold' ? 'badge-warning' : 'badge-secondary')) 
                            }}">
                                {{ str_replace('_', ' ', ucfirst($project->status)) }}
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-base-content/70">Created By</span>
                            <span>{{ $project->createdBy->name }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-base-content/70">Timeline</span>
                            <span>{{ $project->start_date?->format('M d') ?? 'Not set' }} - {{ $project->end_date?->format('M d, Y') ?? 'Not set' }}</span>
                        </div>
                    </div>

                    <div class="card-actions justify-between items-center mt-4">
                        <div class="avatar-group -space-x-6">
                            @forelse($project->members as $member)
                                @if($loop->iteration <= 3)
                                <div class="avatar">
                                    <div class="w-8">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}" />
                                    </div>
                                </div>
                                @endif
                            @empty
                                <div class="avatar placeholder">
                                    <div class="w-8 bg-neutral text-neutral-content">
                                        <span>0</span>
                                    </div>
                                </div>
                            @endforelse
                            @if($project->members->count() > 3)
                                <div class="avatar placeholder">
                                    <div class="w-8 bg-neutral text-neutral-content">
                                        <span>+{{ $project->members->count() - 3 }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <a href="{{ route('projects.show', $project) }}" class="btn btn-primary btn-sm">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-10">
                    <div class="avatar placeholder mb-4">
                        <div class="bg-neutral text-neutral-content rounded-full w-16">
                            <iconify-icon icon="lucide:folder" class="w-8 h-8"></iconify-icon>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold">No Projects Found</h3>
                    <p class="text-base-content/70 mt-1">Get started by creating a new project</p>
                    <a href="{{ route('projects.create') }}" class="btn btn-primary mt-4">
                        <iconify-icon icon="lucide:plus" class="mr-2"></iconify-icon> New Project
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($projects->hasPages())
        <div class="mt-6">
            {{ $projects->links() }}
        </div>
    @endif
</div> 