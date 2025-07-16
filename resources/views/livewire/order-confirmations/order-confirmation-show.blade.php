<div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
    <div class="sm:flex sm:items-start">
        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Order Confirmation Details</h3>
            
            <div class="mt-6 border-t border-gray-200">
                <dl class="divide-y divide-gray-200">
                    <!-- Project -->
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Project</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            {{ $confirmation->project->name }}
                        </dd>
                    </div>

                    <!-- Product -->
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Product</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            {{ $confirmation->product_name }}
                        </dd>
                    </div>

                    <!-- Client Info -->
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Client Information</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <p class="font-medium">{{ $confirmation->client_name }}</p>
                            <p class="text-gray-500">{{ $confirmation->client_number }}</p>
                            <p class="text-gray-500">{{ $confirmation->client_address }}</p>
                        </dd>
                    </div>

                    <!-- Confirmation Date -->
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Confirmation Date</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            {{ $confirmation->confirmation_date->format('M d, Y') }}
                        </dd>
                    </div>

                    <!-- Confirmed By -->
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Confirmed By</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            {{ $confirmation->confirmedBy->user->name }}
                        </dd>
                    </div>

                    <!-- Status -->
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1 text-sm sm:col-span-2 sm:mt-0">
                            <span class="inline-flex items-center rounded-full px-3 py-0.5 text-sm font-medium {{ 
                                $confirmation->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                ($confirmation->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') 
                            }}">
                                {{ ucfirst($confirmation->status) }}
                            </span>
                            
                            <!-- Status Actions -->
                            <div class="mt-2">
                                @if($confirmation->status !== 'confirmed')
                                    <button
                                        wire:click="updateStatus('confirmed')"
                                        type="button"
                                        class="inline-flex items-center rounded-md border border-transparent bg-green-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                    >
                                        Mark as Confirmed
                                    </button>
                                @endif
                                
                                @if($confirmation->status !== 'cancelled')
                                    <button
                                        wire:click="updateStatus('cancelled')"
                                        type="button"
                                        class="ml-3 inline-flex items-center rounded-md border border-transparent bg-red-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                    >
                                        Mark as Cancelled
                                    </button>
                                @endif
                                
                                @if($confirmation->status !== 'pending')
                                    <button
                                        wire:click="updateStatus('pending')"
                                        type="button"
                                        class="ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    >
                                        Mark as Pending
                                    </button>
                                @endif
                            </div>
                        </dd>
                    </div>

                    <!-- Notes -->
                    @if($confirmation->notes)
                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500">Notes</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                {{ $confirmation->notes }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
        <button
            wire:click="$dispatch('closeModal')"
            type="button"
            class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm"
        >
            Close
        </button>
    </div>
</div>
