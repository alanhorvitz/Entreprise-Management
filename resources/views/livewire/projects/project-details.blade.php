<div class="max-w-7xl mx-auto ">
    <!-- Project Header -->
    <div class="bg-base-100 shadow-xl rounded-box">
        <div class="p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold">{{ $project->name }}</h1>
                    <p class="text-base-content/70 mt-2">{{ $project->description }}</p>
                </div>
                <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-sm btn-circle">
                        <iconify-icon icon="lucide:more-vertical"></iconify-icon>
                    </div>
                    <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                        <li><a href="{{ route('projects.edit', $project) }}">
                            <iconify-icon icon="lucide:edit"></iconify-icon> Edit Project
                        </a></li>
                        <li><a class="text-error">
                            <iconify-icon icon="lucide:trash-2"></iconify-icon> Delete Project
                        </a></li>
                    </ul>
                </div>
            </div>

            <!-- Project Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
                <div class="stat bg-base-200 rounded-lg p-4">
                    <div class="stat-title">Status</div>
                    <div class="stat-value">
                        <span class="badge {{ 
                            $project->status === 'completed' ? 'badge-success' : 
                            ($project->status === 'in_progress' ? 'badge-primary' : 
                            ($project->status === 'on_hold' ? 'badge-warning' : 'badge-secondary')) 
                        }} text-sm">
                            {{ str_replace('_', ' ', ucfirst($project->status)) }}
                        </span>
                    </div>
                </div>
                
                <div class="stat bg-base-200 rounded-lg p-4">
                    <div class="stat-title">Created By</div>
                    <div class="stat-value text-sm">{{ $project->createdBy->name }}</div>
                </div>

                <div class="stat bg-base-200 rounded-lg p-4">
                    <div class="stat-title">Timeline</div>
                    <div class="stat-value text-sm">
                        {{ $project->start_date?->format('M d') ?? 'Not set' }} - {{ $project->end_date?->format('M d, Y') ?? 'Not set' }}
                    </div>
                </div>

                <div class="stat bg-base-200 rounded-lg p-4">
                    <div class="stat-title">Supervisor</div>
                    <div class="stat-value text-sm">{{ $project->supervisedBy?->name ?? 'Not assigned' }}</div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs tabs-bordered px-6">
            <button wire:click="setActiveTab('overview')" 
                class="tab tab-lg {{ $activeTab === 'overview' ? 'tab-active' : '' }}">
                Overview
            </button>
            <button wire:click="setActiveTab('tasks')" 
                class="tab tab-lg {{ $activeTab === 'tasks' ? 'tab-active' : '' }}">
                Tasks
            </button>
            <button wire:click="setActiveTab('members')" 
                class="tab tab-lg {{ $activeTab === 'members' ? 'tab-active' : '' }}">
                Team Members
            </button>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="mt-6">
        @if($activeTab === 'overview')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Project Information -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">Project Information</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-base-content/70">Description</label>
                                <p class="mt-1">{{ $project->description }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-base-content/70">Created At</label>
                                <p class="mt-1">{{ $project->created_at->format('M d, Y H:i') }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-base-content/70">Last Updated</label>
                                <p class="mt-1">{{ $project->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Team Members -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <div class="flex justify-between items-center">
                            <h2 class="card-title">Team Members</h2>
                            <span class="badge badge-neutral">{{ $members->count() }} members</span>
                        </div>
                        <div class="space-y-4 mt-4">
                            @foreach($members->take(5) as $member)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="avatar">
                                            <div class="w-8 h-8">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}" />
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-bold">{{ $member->name }}</div>
                                            <div class="text-sm text-base-content/70">{{ $member->pivot->role }}</div>
                                        </div>
                                    </div>
                                    <div class="text-sm text-base-content/70">
                                        Joined {{ $member->pivot->joined_at->diffForHumans() }}
                                    </div>
                                </div>
                            @endforeach
                            @if($members->count() > 5)
                                <button wire:click="setActiveTab('members')" class="btn btn-ghost btn-sm w-full">
                                    View All Members
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        @elseif($activeTab === 'tasks')
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <div class="flex justify-between items-center">
                        <h2 class="card-title">Tasks</h2>
                        <button class="btn btn-primary btn-sm">
                            <iconify-icon icon="lucide:plus" class="mr-2"></iconify-icon>
                            New Task
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto mt-4">
                        @if($tasks->count() > 0)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Due Date</th>
                                        <th>Created By</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tasks as $task)
                                        <tr>
                                            <td>{{ $task->title }}</td>
                                            <td>
                                                <span class="badge {{ 
                                                    $task->status === 'completed' ? 'badge-success' : 
                                                    ($task->status === 'in_progress' ? 'badge-primary' : 'badge-secondary') 
                                                }}">
                                                    {{ str_replace('_', ' ', ucfirst($task->status)) }}
                                                </span>
                                            </td>
                                            <td>{{ $task->due_date?->format('M d, Y') ?? 'No due date' }}</td>
                                            <td>{{ $task->createdBy->name }}</td>
                                            <td>
                                                <button class="btn btn-ghost btn-sm">
                                                    <iconify-icon icon="lucide:eye"></iconify-icon>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                            @if($tasks->hasPages())
                                <div class="mt-4">
                                    {{ $tasks->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-8">
                                <div class="avatar placeholder mb-4">
                                    <div class="bg-neutral text-neutral-content rounded-full w-16">
                                        <iconify-icon icon="lucide:check-circle" class="w-8 h-8"></iconify-icon>
                                    </div>
                                </div>
                                <h3 class="text-lg font-semibold">No Tasks Yet</h3>
                                <p class="text-base-content/70 mt-1">Create your first task to get started</p>
                                <button class="btn btn-primary mt-4">
                                    <iconify-icon icon="lucide:plus" class="mr-2"></iconify-icon>
                                    New Task
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        @elseif($activeTab === 'members')
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <div class="flex justify-between items-center">
                        <h2 class="card-title">Team Members</h2>
                        <button onclick="add_member_modal.showModal()" class="btn btn-primary btn-sm">
                            <iconify-icon icon="lucide:user-plus" class="w-4 h-4"></iconify-icon>
                            Add Member
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto mt-4">
                        @if($members->count() > 0)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Member</th>
                                        <th>Role</th>
                                        <th>Joined</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($members as $member)
                                        <tr>
                                            <td>
                                                <div class="flex items-center space-x-3">
                                                    <div class="avatar">
                                                        <div class="w-8 h-8">
                                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}" />
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="font-bold">{{ $member->name }}</div>
                                                        <div class="text-sm text-base-content/70">{{ $member->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ ucfirst($member->pivot->role) }}</td>
                                            <td>{{ $member->pivot->joined_at->format('M d, Y') }}</td>
                                            <td>
                                                <button class="btn btn-ghost btn-sm">
                                                    <iconify-icon icon="lucide:more-vertical"></iconify-icon>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center py-8">
                                <div class="avatar placeholder mb-4">
                                    <div class="bg-neutral text-neutral-content rounded-full w-16">
                                        <iconify-icon icon="lucide:users" class="w-8 h-8"></iconify-icon>
                                    </div>
                                </div>
                                <h3 class="text-lg font-semibold">No Team Members</h3>
                                <p class="text-base-content/70 mt-1">Add team members to collaborate on this project</p>
                                <button onclick="add_member_modal.showModal()" class="btn btn-primary mt-4">
                                    <iconify-icon icon="lucide:user-plus" class="w-4 h-4 mr-2"></iconify-icon>
                                    Add Member
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Add this at the bottom of your view -->
    <livewire:projects.add-member-modal :project="$project" />
</div> 