<div>
    <!-- Filters Section -->
    <div class="card bg-base-200 shadow-md mb-6">
        <div class="card-body">
            <div class="flex justify-between items-center mb-5">
                <h2 class="card-title">Order Confirmations</h2>
                
                <button
                    wire:click="$dispatch('openModal', { component: 'order-confirmations.order-confirmation-create' })"
                    class="btn btn-primary"
                >
                    <span class="iconify w-5 h-5 mr-2" data-icon="solar:add-circle-bold-duotone"></span>
                    Add Confirmation
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Project Filter -->
                <div class="form-control">
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

                <!-- Status Filter -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Status</span>
                    </label>
                    <select class="select select-bordered w-full" wire:model.live="statusFilter">
                        <option value="">All Statuses</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>

                <!-- Date Filter -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Date</span>
                    </label>
                    <input
                        type="date"
                        wire:model.live="dateFilter"
                        class="input input-bordered w-full"
                    >
                </div>

                <!-- Search -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Search</span>
                    </label>
                    <div class="join w-full">
                        <input
                            type="text"
                            wire:model.live="search"
                            class="input input-bordered join-item w-full"
                            placeholder="Search confirmations..."
                        >
                        <button class="btn join-item">
                            <span class="iconify w-5 h-5" data-icon="solar:magnifer-bold-duotone"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmations Table -->
    <div class="overflow-x-auto bg-base-200 rounded-lg shadow-md">
        <table class="table table-zebra w-full">
            <thead>
                <tr>
                    <th>
                        <button wire:click="sortBy('product_name')" class="group inline-flex items-center">
                            Product
                            @if($sortField === 'product_name')
                                <span class="ml-2">
                                    @if($sortDirection === 'asc')
                                        <span class="iconify w-4 h-4" data-icon="solar:sort-from-bottom-to-top-bold-duotone"></span>
                                    @else
                                        <span class="iconify w-4 h-4" data-icon="solar:sort-from-top-to-bottom-bold-duotone"></span>
                                    @endif
                                </span>
                            @endif
                        </button>
                    </th>
                    <th>
                        <button wire:click="sortBy('client_name')" class="group inline-flex items-center">
                            Client
                            @if($sortField === 'client_name')
                                <span class="ml-2">
                                    @if($sortDirection === 'asc')
                                        <span class="iconify w-4 h-4" data-icon="solar:sort-from-bottom-to-top-bold-duotone"></span>
                                    @else
                                        <span class="iconify w-4 h-4" data-icon="solar:sort-from-top-to-bottom-bold-duotone"></span>
                                    @endif
                                </span>
                            @endif
                        </button>
                    </th>
                    <th>Project</th>
                    <th>
                        <button wire:click="sortBy('confirmation_date')" class="group inline-flex items-center">
                            Date
                            @if($sortField === 'confirmation_date')
                                <span class="ml-2">
                                    @if($sortDirection === 'asc')
                                        <span class="iconify w-4 h-4" data-icon="solar:sort-from-bottom-to-top-bold-duotone"></span>
                                    @else
                                        <span class="iconify w-4 h-4" data-icon="solar:sort-from-top-to-bottom-bold-duotone"></span>
                                    @endif
                                </span>
                            @endif
                        </button>
                    </th>
                    <th>Status</th>
                    <th class="w-20">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($confirmations as $confirmation)
                    <tr class="hover">
                        <td>
                            <div class="font-bold hover:text-primary cursor-pointer" wire:click="$dispatch('openModal', { component: 'order-confirmations.order-confirmation-show', arguments: { confirmationId: {{ $confirmation->id }} }})">
                                {{ $confirmation->product_name }}
                            </div>
                        </td>
                        <td>
                            <div>
                                <div class="font-medium">{{ $confirmation->client_name }}</div>
                                <div class="text-xs text-base-content/70">{{ $confirmation->client_number }}</div>
                            </div>
                        </td>
                        <td>{{ $confirmation->project->name }}</td>
                        <td>{{ $confirmation->confirmation_date->format('M d, Y') }}</td>
                        <td>
                            <span class="badge badge-sm whitespace-nowrap !px-2 min-w-[60px] text-center {{ 
                                $confirmation->status === 'confirmed' ? 'badge-success' : 
                                ($confirmation->status === 'cancelled' ? 'badge-error' : 'badge-warning') 
                            }}">
                                {{ ucfirst($confirmation->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="dropdown dropdown-end">
                                <div tabindex="0" role="button" class="btn btn-ghost btn-xs">
                                    <span class="iconify w-5 h-5" data-icon="solar:menu-dots-bold-duotone"></span>
                                </div>
                                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-200 rounded-box w-52">
                                    <li>
                                        <a wire:click="$dispatch('openModal', { component: 'order-confirmations.order-confirmation-show', arguments: { confirmationId: {{ $confirmation->id }} }})" @click="document.activeElement.blur()">
                                            <span class="iconify w-5 h-5 mr-2" data-icon="solar:eye-bold-duotone"></span> View Details
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <span class="iconify w-10 h-10 text-base-content/50" data-icon="solar:clipboard-list-bold-duotone"></span>
                                <p class="text-base-content/70">No confirmations found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $confirmations->links() }}
    </div>
</div>
