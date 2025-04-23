@extends('layout.app')

@section('title', 'Edit Project')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Edit Project
            </h2>
        </div>
        
        <livewire:edit-project :project="$project" />
    </div>
</div>
@endsection 