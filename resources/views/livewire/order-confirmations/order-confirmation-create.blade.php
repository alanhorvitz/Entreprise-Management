<div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
    <div class="sm:flex sm:items-start">
        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Add Order Confirmation</h3>
            <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- Project -->
                <div class="sm:col-span-3">
                    <label for="project_id" class="block text-sm font-medium text-gray-700">Project</label>
                    <div class="mt-1">
                        <select
                            wire:model="project_id"
                            id="project_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        >
                            <option value="">Select a project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                        @error('project_id') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Product Name -->
                <div class="sm:col-span-3">
                    <label for="product_name" class="block text-sm font-medium text-gray-700">Product Name</label>
                    <div class="mt-1">
                        <input
                            type="text"
                            wire:model="product_name"
                            id="product_name"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        >
                        @error('product_name') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Client Name -->
                <div class="sm:col-span-3">
                    <label for="client_name" class="block text-sm font-medium text-gray-700">Client Name</label>
                    <div class="mt-1">
                        <input
                            type="text"
                            wire:model="client_name"
                            id="client_name"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        >
                        @error('client_name') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Client Number -->
                <div class="sm:col-span-3">
                    <label for="client_number" class="block text-sm font-medium text-gray-700">Client Number</label>
                    <div class="mt-1">
                        <input
                            type="text"
                            wire:model="client_number"
                            id="client_number"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        >
                        @error('client_number') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Client Address -->
                <div class="sm:col-span-6">
                    <label for="client_address" class="block text-sm font-medium text-gray-700">Client Address</label>
                    <div class="mt-1">
                        <textarea
                            wire:model="client_address"
                            id="client_address"
                            rows="3"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        ></textarea>
                        @error('client_address') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Confirmation Date -->
                <div class="sm:col-span-3">
                    <label for="confirmation_date" class="block text-sm font-medium text-gray-700">Confirmation Date</label>
                    <div class="mt-1">
                        <input
                            type="date"
                            wire:model="confirmation_date"
                            id="confirmation_date"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        >
                        @error('confirmation_date') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Status -->
                <div class="sm:col-span-3">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <div class="mt-1">
                        <select
                            wire:model="status"
                            id="status"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        >
                            <option value="confirmed">Confirmed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="pending">Pending</option>
                        </select>
                        @error('status') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Notes -->
                <div class="sm:col-span-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <div class="mt-1">
                        <textarea
                            wire:model="notes"
                            id="notes"
                            rows="3"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        ></textarea>
                        @error('notes') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
        <button
            wire:click="create"
            type="button"
            class="inline-flex w-full justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
        >
            Create Confirmation
        </button>
        <button
            wire:click="$dispatch('closeModal')"
            type="button"
            class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm"
        >
            Cancel
        </button>
    </div>
</div>
