<div>
    <div class="modal-header">
        <h3 class="text-lg font-bold">{{ $task->title }}</h3>
    </div>
    
    <div class="modal-body">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Task Info -->
            <div>
                <div class="mb-4">
                    <h4 class="font-semibold text-sm text-gray-500 mb-1">Description</h4>
                    <p class="text-base">{{ $task->description ?: 'No description provided' }}</p>
                </div>
                
                <div class="mb-4">
                    <h4 class="font-semibold text-sm text-gray-500 mb-1">Project</h4>
                    <p class="text-base">{{ $task->project->name ?? 'N/A' }}</p>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <h4 class="font-semibold text-sm text-gray-500 mb-1">Start Date</h4>
                        <p class="text-base">{{ $task->start_date ? $task->start_date->format('M d, Y') : 'Not set' }}</p>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-sm text-gray-500 mb-1">Due Date</h4>
                        <p class="text-base">{{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <h4 class="font-semibold text-sm text-gray-500 mb-1">Priority</h4>
                        @php
                            $priorityClass = [
                                'high' => 'badge-error',
                                'medium' => 'badge-warning',
                                'low' => 'badge-info',
                            ][$task->priority] ?? 'badge-ghost';
                        @endphp
                        <span class="badge {{ $priorityClass }}">{{ ucfirst($task->priority) }}</span>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-sm text-gray-500 mb-1">Status</h4>
                        <div class="flex gap-2">
                            <div class="dropdown dropdown-hover">
                                @php
                                    $statusClass = [
                                        'todo' => 'badge-secondary',
                                        'in_progress' => 'badge-primary',
                                        'completed' => 'badge-success',
                                    ][$task->current_status] ?? 'badge-ghost';
                                    
                                    $statusLabel = [
                                        'todo' => 'Not Started',
                                        'in_progress' => 'In Progress',
                                        'completed' => 'Completed',
                                    ][$task->current_status] ?? $task->current_status;
                                @endphp
                                <div tabindex="0" role="button" class="badge {{ $statusClass }}">{{ $statusLabel }}</div>
                                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                                    <li><a wire:click="updateStatus('todo')" class="cursor-pointer {{ $task->current_status === 'todo' ? 'active' : '' }}">Not Started</a></li>
                                    <li><a wire:click="updateStatus('in_progress')" class="cursor-pointer {{ $task->current_status === 'in_progress' ? 'active' : '' }}">In Progress</a></li>
                                    <li><a wire:click="updateStatus('completed')" class="cursor-pointer {{ $task->current_status === 'completed' ? 'active' : '' }}">Completed</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h4 class="font-semibold text-sm text-gray-500 mb-1">Approval Status</h4>
                    <span class="badge {{ $task->status === 'approved' ? 'badge-success' : 'badge-warning' }}">
                        {{ $task->status === 'approved' ? 'Approved' : 'Pending Approval' }}
                    </span>
                </div>
                
                <div class="mb-4">
                    <h4 class="font-semibold text-sm text-gray-500 mb-1">Created By</h4>
                    <div class="flex items-center gap-2">
                        <div class="avatar">
                            <div class="w-6 rounded-full">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($task->createdBy?->name ?? 'User') }}&background=random" alt="{{ $task->createdBy?->name ?? 'User' }}" />
                            </div>
                        </div>
                        <span>{{ $task->createdBy?->name ?? 'Unknown User' }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Assignees & Comments -->
            <div>
                <div class="mb-6">
                    <h4 class="font-semibold text-sm text-gray-500 mb-2">Assigned To</h4>
                    @if($task->assignedUsers->count() > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($task->assignedUsers as $user)
                                <div class="flex items-center p-2 bg-base-200 rounded-lg">
                                    <div class="avatar mr-2">
                                        <div class="w-6 rounded-full">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'User') }}&background=random" alt="{{ $user->name ?? 'User' }}" />
                                        </div>
                                    </div>
                                    <span>{{ $user->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No one assigned yet</p>
                    @endif
                </div>
                
                <div>
                    <h4 class="font-semibold text-sm text-gray-500 mb-2">Comments</h4>
                    
                    <div class="mb-4 max-h-60 overflow-y-auto space-y-3">
                        @forelse($task->taskComments as $comment)
                            <div class="bg-base-200 p-3 rounded-lg">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="avatar">
                                            <div class="w-6 rounded-full">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->user?->name ?? 'User') }}&background=random" alt="{{ $comment->user?->name ?? 'User' }}" />
                                            </div>
                                        </div>
                                        <span class="font-medium">{{ $comment->user?->name ?? 'Unknown User' }}</span>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm">{{ $comment->comment }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500">No comments yet</p>
                        @endforelse
                    </div>
                    
                    <form wire:submit.prevent="addComment" class="mt-3">
                        <div class="form-control">
                            <textarea class="textarea textarea-bordered h-20" placeholder="Add a comment..." wire:model="comment" required></textarea>
                            @error('comment') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex justify-end mt-2">
                            <button type="submit" class="btn btn-sm btn-primary">Add Comment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal-footer mt-6 flex justify-between">
        <div>
            <button wire:click="openEditModal()" class="btn btn-sm btn-outline">
                <span class="iconify w-5 h-5 mr-2" data-icon="solar:pen-bold-duotone"></span> Edit Task
            </button>
        </div>
        <button type="button" class="btn" wire:click="$dispatch('closeModal')">Close</button>
    </div>
</div> 