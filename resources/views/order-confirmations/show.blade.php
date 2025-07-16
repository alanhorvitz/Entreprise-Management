@extends('layout.app')

@section('title', 'Order Confirmation Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="card bg-base-200 shadow-xl">
        <div class="card-body">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="card-title text-2xl">Order Confirmation Details</h2>
                    <p class="text-base-content/70 mt-1">View and manage order confirmation information</p>
                </div>
                <a href="{{ route('order-confirmations.index') }}" class="btn btn-ghost btn-sm">
                    <span class="iconify w-5 h-5 mr-2" data-icon="solar:arrow-left-bold-duotone"></span>
                    Back to List
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Project -->
                <div>
                    <h3 class="font-medium text-base-content/70">Project</h3>
                    <p class="text-lg mt-1">{{ $orderConfirmation->project->name }}</p>
                </div>

                <!-- Product -->
                <div>
                    <h3 class="font-medium text-base-content/70">Product</h3>
                    <p class="text-lg mt-1">{{ $orderConfirmation->product_name }}</p>
                </div>

                <!-- Client Information -->
                <div class="md:col-span-2">
                    <h3 class="font-medium text-base-content/70">Client Information</h3>
                    <div class="mt-1">
                        <p class="text-lg font-medium">{{ $orderConfirmation->client_name }}</p>
                        <p class="text-base-content/70">{{ $orderConfirmation->client_number }}</p>
                        <p class="text-base-content/70">{{ $orderConfirmation->client_address }}</p>
                    </div>
                </div>

                <!-- Confirmation Date -->
                <div>
                    <h3 class="font-medium text-base-content/70">Confirmation Date</h3>
                    <p class="text-lg mt-1">{{ $orderConfirmation->confirmation_date->format('M d, Y') }}</p>
                </div>

                <!-- Status -->
                <div>
                    <h3 class="font-medium text-base-content/70">Status</h3>
                    <div class="mt-1">
                        <span class="badge badge-lg {{ 
                            $orderConfirmation->status === 'confirmed' ? 'badge-success' : 
                            ($orderConfirmation->status === 'cancelled' ? 'badge-error' : 'badge-warning') 
                        }}">
                            {{ ucfirst($orderConfirmation->status) }}
                        </span>

                        <!-- Status Actions -->
                        <div class="mt-4 flex flex-wrap gap-2">
                            @if($orderConfirmation->status !== 'confirmed')
                                <form action="{{ route('order-confirmations.update-status', $orderConfirmation) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        Mark as Confirmed
                                    </button>
                                </form>
                            @endif

                            @if($orderConfirmation->status !== 'cancelled')
                                <form action="{{ route('order-confirmations.update-status', $orderConfirmation) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="btn btn-error btn-sm">
                                        Mark as Cancelled
                                    </button>
                                </form>
                            @endif

                            @if($orderConfirmation->status !== 'pending')
                                <form action="{{ route('order-confirmations.update-status', $orderConfirmation) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="pending">
                                    <button type="submit" class="btn btn-ghost btn-sm">
                                        Mark as Pending
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                @if($orderConfirmation->notes)
                    <div class="md:col-span-2">
                        <h3 class="font-medium text-base-content/70">Notes</h3>
                        <p class="mt-1 whitespace-pre-line">{{ $orderConfirmation->notes }}</p>
                    </div>
                @endif

                <!-- Metadata -->
                <div class="md:col-span-2 border-t pt-4 mt-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-base-content/70">
                        <div>
                            <span>Confirmed by:</span>
                            <span class="font-medium text-base-content">
                                @if($orderConfirmation->confirmedBy)
                                    {{ $orderConfirmation->confirmedBy->user->name }}
                                @else
                                    Admin
                                @endif
                            </span>
                        </div>
                        <div>
                            <span>Created at:</span>
                            <span class="font-medium text-base-content">
                                {{ $orderConfirmation->created_at->format('M d, Y H:i') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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