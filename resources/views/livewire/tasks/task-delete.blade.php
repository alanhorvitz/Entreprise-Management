<div>
    <!-- Confirm Delete Modal -->
    <div class="text-center">
        <span class="iconify text-error text-5xl mb-4" data-icon="lucide--trash-2"></span>
        <h3 class="text-lg font-bold mb-2">Delete Task</h3>
        <p class="mb-4">Are you sure you want to delete <span class="font-semibold">{{ $taskTitle }}</span>? This action cannot be undone.</p>
    </div>
    
    <div class="flex justify-center gap-2 mt-6">
        <button type="button" class="btn" wire:click="$dispatch('closeModal')">Cancel</button>
        <button wire:click="delete" class="btn btn-error">Delete Task</button>
    </div>
</div> 