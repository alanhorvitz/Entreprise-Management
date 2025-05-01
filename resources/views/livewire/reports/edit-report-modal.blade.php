<div class="w-full">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Edit Report</h2>
        <button wire:click="close" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Date Input -->
        <div class="form-control w-full">
            <label class="label">
                <span class="label-text font-medium">Date</span>
            </label>
            <input type="date" wire:model="date" class="input input-bordered w-full" required />
            @error('date') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Summary -->
        <div class="form-control w-full">
            <label class="label">
                <span class="label-text font-medium">Daily Summary</span>
                <span class="label-text-alt text-base-content/70">Optional</span>
            </label>
            <textarea wire:model="summary" 
                    class="textarea textarea-bordered min-h-32 w-full"  
                    placeholder="Write a summary of your day's work and achievements..."></textarea>
            @error('summary') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Tasks Section -->
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="font-medium">Tasks</h3>
                <button type="button" class="btn btn-sm btn-primary" wire:click="addTask">
                    <span class="iconify w-4 h-4 mr-1" data-icon="solar:add-square-bold-duotone"></span>
                    Add Task
                </button>
            </div>

            <div class="space-y-4">
                @foreach($reportTasks as $index => $task)
                    <div class="card bg-base-200">
                        <div class="card-body p-4">
                            <div class="flex justify-between items-center">
                                <select wire:model="reportTasks.{{ $index }}.task_id" 
                                        class="select select-bordered w-full">
                                    <option value="">Select a task</option>
                                    @foreach($availableTasks as $availableTask)
                                        <option value="{{ $availableTask->id }}">{{ $availableTask->title }}</option>
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

        <!-- Submit Button -->
        <div class="flex justify-end mt-8">
            <button type="submit" class="btn btn-primary">
                <span class="iconify w-5 h-5 mr-1" data-icon="solar:check-circle-bold-duotone"></span>
                Update Report
            </button>
        </div>
    </form>
</div> 