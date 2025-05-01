<div class="min-h-screen bg-base-200/50">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold">Create Daily Report</h2>
                <p class="text-base-content/70 mt-1">Record your daily progress and task updates</p>
            </div>
            <a href="{{ route('reports.index') }}" class="btn btn-ghost btn-sm">
                <span class="iconify w-5 h-5 mr-1" data-icon="solar:arrow-left-bold-duotone"></span>
                Back to Reports
            </a>
        </div>

        <form wire:submit.prevent="save" class="space-y-6">
            <div class="grid grid-cols-1 gap-6">
                <!-- Date and Summary Card -->
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body">
                        <!-- Date Input -->
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-medium">Date</span>
                            </label>
                            <input type="date" wire:model="date" class="input input-bordered w-full" required />
                            @error('date') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Summary -->
                        <div class="form-control w-full mt-4 flex flex-col gap-2">
                            <label class="label">
                                <span class="label-text font-medium">Daily Summary</span>
                                <span class="label-text-alt text-base-content/70">Optional</span>
                            </label>
                            <textarea wire:model="summary" 
                                    class="textarea textarea-bordered min-h-32 w-full"  
                                    placeholder="Write a summary of your day's work and achievements..."></textarea>
                            @error('summary') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Tasks Section -->
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-lg font-semibold">Tasks Progress</h3>
                                <p class="text-sm text-base-content/70">Add updates for tasks you worked on today</p>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" wire:click="addTask">
                                <span class="iconify w-4 h-4 mr-1" data-icon="solar:add-square-bold-duotone"></span>
                                Add Task
                            </button>
                        </div>

                        <div class="space-y-4">
                            @foreach($reportTasks as $index => $task)
                                <div class="card bg-base-200">
                                    <div class="card-body">
                                        <div class="flex justify-between items-center">
                                                <select wire:model="reportTasks.{{ $index }}.task_id" 
                                                        class="select select-bordered w-full">
                                                <option value="">Select a task</option>
                                                @foreach($availableTasks as $task)
                                                    <option value="{{ $task->id }}">{{ $task->title }}</option>
                                                    @endforeach
                                                </select>
                                            <button type="button" 
                                                    class="btn btn-ghost btn-sm btn-circle text-error" 
                                                    wire:click="removeTask({{ $index }})">
                                                <span class="iconify w-5 h-5" data-icon="solar:trash-bin-trash-bold-duotone"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if(empty($reportTasks))
                                <div class="text-center py-12 bg-base-200 rounded-lg">
                                    <div class="w-16 h-16 bg-base-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span class="iconify w-8 h-8 text-primary" data-icon="solar:list-check-bold-duotone"></span>
                                    </div>
                                    <h3 class="font-semibold">No Tasks Added Yet</h3>
                                    <p class="text-base-content/70 mt-1">Click the "Add Task" button to start recording your progress</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end mt-8">
                <button type="submit" class="btn btn-primary">
                    <span class="iconify w-5 h-5 mr-1" data-icon="solar:check-circle-bold-duotone"></span>
                    Submit Daily Report
                </button>
            </div>
        </form>
</div> 