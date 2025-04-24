<div>
    <!-- The Modal -->
    <dialog id="add_member_modal" class="modal">
        <div class="modal-box">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg">Add Project Members</h3>
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost">
                        <iconify-icon icon="lucide:x" class="w-5 h-5"></iconify-icon>
                    </button>
                </form>
            </div>

            @if($availableMembers->isEmpty())
                <div class="text-center py-6">
                    <div class="avatar placeholder mb-4">
                        <div class="bg-neutral text-neutral-content rounded-full w-16">
                            <iconify-icon icon="lucide:users" class="w-8 h-8"></iconify-icon>
                        </div>
                    </div>
                    <h3 class="font-semibold">No Available Members</h3>
                    <p class="text-base-content/70 mt-1">No available members to add from this department.</p>
                </div>
            @else
                <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-2">
                    @foreach($availableMembers as $member)
                        <label class="flex items-center gap-4 p-3 rounded-lg hover:bg-base-200 cursor-pointer">
                            <input type="checkbox" 
                                   wire:model="selectedMembers" 
                                   value="{{ $member->id }}" 
                                   class="checkbox checkbox-primary">
                            <div class="flex items-center gap-3 flex-1">
                                <div class="avatar">
                                    <div class="w-10 rounded-full">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}" />
                                    </div>
                                </div>
                                <div>
                                    <div class="font-medium">{{ $member->name }}</div>
                                    <div class="text-sm text-base-content/70">{{ $member->email }}</div>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>

                <div class="modal-action mt-6">
                    <form method="dialog" class="flex gap-2">
                        <button class="btn">Cancel</button>
                        <button wire:click="addMembers" type="button" class="btn btn-primary">
                            Add Selected Members
                        </button>
                    </form>
                </div>
            @endif
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</div>
