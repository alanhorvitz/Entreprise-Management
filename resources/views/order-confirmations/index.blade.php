@extends('layout.app')

@section('title', 'Order Confirmations')

@section('content')
<div class="space-y-6">
    <!-- Filters Section -->
    <div class="card bg-base-200 shadow-md mb-6">
        <div class="card-body">
            <div class="flex justify-between items-center mb-5">
                <h2 class="card-title">Order Confirmations</h2>
                <div class="flex gap-2">
                    <a href="{{ route('order-confirmations.create') }}" class="btn btn-primary">
                        <span class="iconify w-5 h-5 mr-2" data-icon="solar:add-circle-bold-duotone"></span>
                        Add Confirmation
                    </a>
                    @if(auth()->user()->hasRole(['admin', 'director']))
                        <a href="{{ route('order-confirmations.users-report') }}" class="btn btn-secondary">
                            <span class="iconify w-5 h-5 mr-2" data-icon="solar:users-group-rounded-bold-duotone"></span>
                            Users Confirmations Report
                        </a>
                    @endif
                </div>
            </div>

            <form method="GET" action="{{ route('order-confirmations.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Project Filter -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Project</span>
                    </label>
                    <select name="project_id" class="select select-bordered w-full" onchange="this.form.submit()">
                        <option value="">All Projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Status</span>
                    </label>
                    <select name="status" class="select select-bordered w-full" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>

                <!-- Date Filter -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Date</span>
                    </label>
                    <input 
                        type="date" 
                        name="date" 
                        class="input input-bordered w-full" 
                        value="{{ request('date') }}"
                        onchange="this.form.submit()"
                    >
                </div>

                <!-- Search -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Search</span>
                    </label>
                    <div class="flex gap-2">
                        <input 
                            type="text" 
                            name="search" 
                            class="input input-bordered w-full" 
                            placeholder="Search confirmations..."
                            value="{{ request('search') }}"
                        >
                        <button type="submit" class="btn btn-primary">
                            <span class="iconify w-5 h-5" data-icon="solar:search-bold-duotone"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Confirmations Table -->
    <div class="overflow-x-auto bg-base-200 rounded-lg shadow">
        <table class="table table-zebra w-full">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Client</th>
                    <th>Project</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($confirmations as $confirmation)
                    <tr class="hover">
                        <td>
                            <div class="font-bold hover:text-primary">
                                <a href="{{ route('order-confirmations.show', $confirmation) }}">
                                    {{ $confirmation->product_name }}
                                </a>
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
                                        <a href="{{ route('order-confirmations.show', $confirmation) }}">
                                            <span class="iconify w-5 h-5 mr-2" data-icon="solar:eye-bold-duotone"></span> 
                                            View Details
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

@if(session('success'))
    <div class="toast toast-top toast-end">
        <div class="alert alert-success">
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="toast toast-top toast-end">
        <div class="alert alert-error">
            <span>{{ session('error') }}</span>
        </div>
    </div>
@endif
@endsection 