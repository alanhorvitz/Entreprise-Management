@extends('layout.app')

@section('title', 'Create Order Confirmation')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title text-2xl mb-6">Create Order Confirmation</h2>

            <form action="{{ route('order-confirmations.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Project -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text required">Project</span>
                        </label>
                        <select 
                            name="project_id" 
                            class="select select-bordered w-full @error('project_id') select-error @enderror"
                            required
                        >
                            <option value="">Select a project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Product Name -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text required">Product Name</span>
                        </label>
                        <input 
                            type="text" 
                            name="product_name" 
                            class="input input-bordered w-full @error('product_name') input-error @enderror"
                            value="{{ old('product_name') }}"
                            required
                        >
                        @error('product_name')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Client Name -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text required">Client Name</span>
                        </label>
                        <input 
                            type="text" 
                            name="client_name" 
                            class="input input-bordered w-full @error('client_name') input-error @enderror"
                            value="{{ old('client_name') }}"
                            required
                        >
                        @error('client_name')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Client Number -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text required">Client Number</span>
                        </label>
                        <input 
                            type="text" 
                            name="client_number" 
                            class="input input-bordered w-full @error('client_number') input-error @enderror"
                            value="{{ old('client_number') }}"
                            required
                        >
                        @error('client_number')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Client Address -->
                    <div class="form-control md:col-span-2">
                        <label class="label">
                            <span class="label-text required">Client Address</span>
                        </label>
                        <textarea 
                            name="client_address" 
                            class="textarea textarea-bordered w-full @error('client_address') textarea-error @enderror"
                            rows="3"
                            required
                        >{{ old('client_address') }}</textarea>
                        @error('client_address')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Confirmation Date -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text required">Confirmation Date</span>
                        </label>
                        <input 
                            type="date" 
                            name="confirmation_date" 
                            class="input input-bordered w-full @error('confirmation_date') input-error @enderror"
                            value="{{ old('confirmation_date', date('Y-m-d')) }}"
                            required
                        >
                        @error('confirmation_date')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Status -->
                    <input type="hidden" name="status" value="confirmed">

                    <!-- Notes -->
                    <div class="form-control md:col-span-2">
                        <label class="label">
                            <span class="label-text">Notes</span>
                        </label>
                        <textarea 
                            name="notes" 
                            class="textarea textarea-bordered w-full @error('notes') textarea-error @enderror"
                            rows="3"
                        >{{ old('notes') }}</textarea>
                        @error('notes')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-4 mt-6">
                    <a href="{{ route('order-confirmations.index') }}" class="btn btn-ghost">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Create Confirmation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(session('error'))
    <div class="toast toast-top toast-end">
        <div class="alert alert-error">
            <span>{{ session('error') }}</span>
        </div>
    </div>
@endif

<style>
    .required::after {
        content: " *";
        color: #f87272;
    }
</style>
@endsection 