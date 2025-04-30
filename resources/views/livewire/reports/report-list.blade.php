<div>
    <!-- Header with Create Button -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Daily Reports</h2>
        <a href="{{ route('reports.create') }}" class="btn btn-primary">
            <span class="iconify w-5 h-5 mr-1" data-icon="solar:add-square-bold-duotone"></span>
            Create Report
        </a>
    </div>

    <!-- Report Controls -->
    <div class="card bg-base-100 shadow-md mb-6">
        <div class="card-body p-4">
            <div class="flex flex-col lg:flex-row justify-between gap-4">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="form-control w-full sm:max-w-xs">
                        <label class="label">
                            <span class="label-text">Date Range</span>
                        </label>
                        <select class="select select-bordered w-full" wire:model.live="dateRange" wire:change="setDateRange($event.target.value)">
                            @foreach($dateRangeOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-control w-full sm:max-w-xs">
                        <label class="label">
                            <span class="label-text">Project</span>
                        </label>
                        <select class="select select-bordered w-full" wire:model.live="projectFilter">
                            <option value="">All Projects</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-control w-full sm:max-w-xs">
                        <label class="label">
                            <span class="label-text">Department</span>
                        </label>
                        <select class="select select-bordered w-full" wire:model.live="departmentFilter">
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="form-control w-full lg:max-w-xs">
                    <label class="label">
                        <span class="label-text">Search Assignee</span>
                    </label>
                    <div class="join w-full">
                        <input class="input input-bordered join-item w-full" 
                               wire:model.live.debounce.300ms="searchAssignee" 
                               placeholder="Search by name..." />
                        <button class="btn join-item">
                            <span class="iconify w-5 h-5" data-icon="solar:magnifer-bold-duotone"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports List -->
    <div class="space-y-6">
        @forelse($reports as $report)
            <div class="card bg-base-100 shadow-md">
                <div class="card-body p-4">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-4">
                            <div class="avatar">
                                <div class="w-12 rounded-full">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($report->user->first_name . ' ' . $report->user->last_name) }}" 
                                         alt="{{ $report->user->first_name }} {{ $report->user->last_name }}" />
                                </div>
                            </div>
                            <div>
                                <h3 class="font-semibold">{{ $report->user->first_name }} {{ $report->user->last_name }}</h3>
                                <p class="text-sm text-base-content/70">
                                    {{ $report->user->departments->first()?->name ?? 'No Department' }}
                                </p>
                                <p class="text-sm text-base-content/70">
                                    Submitted {{ $report->submitted_at?->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            <div class="badge badge-primary">{{ $report->reportTasks->sum('hours_spent') }} hours</div>
                            <span class="text-sm text-base-content/70">{{ $report->date->format('F j, Y') }}</span>
                        </div>
                    </div>

                    @if($report->summary)
                        <div class="mb-4">
                            <h4 class="font-medium mb-2">Daily Summary</h4>
                            <p class="text-base-content/80">{{ $report->summary }}</p>
                        </div>
                    @endif

                    <div>
                        <h4 class="font-medium mb-2">Tasks Worked On</h4>
                        <div class="space-y-3">
                            @foreach($report->reportTasks as $reportTask)
                                <div class="bg-base-200 p-3 rounded-lg">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <h5 class="font-medium">{{ $reportTask->task->title }}</h5>
                                            <span class="text-sm text-base-content/70">{{ $reportTask->task->project->name }}</span>
                                        </div>
                                        <div class="flex gap-2">
                                            <span class="badge badge-{{ $reportTask->task->current_status === 'completed' ? 'success' : 'info' }}">
                                                {{ ucfirst($reportTask->task->current_status) }}
                                            </span>
                                        </div>
                                    </div>
                                    @if($reportTask->progress_notes)
                                        <p class="text-sm text-base-content/70">{{ $reportTask->progress_notes }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="card-actions justify-end mt-4">
                        <button class="btn btn-sm btn-outline" wire:click="showAssigneeReport({{ $report->user_id }})">
                            <span class="iconify w-4 h-4 mr-1" data-icon="solar:user-id-bold-duotone"></span>
                            View All Reports
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-10">
                <div class="w-16 h-16 bg-neutral text-neutral-content rounded-full inline-flex items-center justify-center mb-4 mx-auto">
                    <span class="iconify w-8 h-8" data-icon="solar:notebook-bold-duotone"></span>
                </div>
                <h3 class="text-lg font-semibold">No Reports Found</h3>
                <p class="text-base-content/70 mt-1">Try adjusting your filters</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="flex justify-center mt-8">
        {{ $reports->links() }}
    </div>

    <!-- Assignee Report Modal -->
    @if($showAssigneeModal && $selectedAssigneeId)
        <div class="modal modal-open">
            <div class="modal-box w-11/12 max-w-5xl">
                <livewire:reports.assignee-report-modal :userId="$selectedAssigneeId" />
            </div>
            <div class="modal-backdrop" wire:click="closeAssigneeModal">
                <button class="cursor-pointer">close</button>
            </div>
        </div>
    @endif
</div>
