@extends('layout.app')

@section('title', 'Project Management')

@section('content')
<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <div class="flex justify-between items-center mb-5">
            <h2 class="card-title">Projects</h2>
            
            <a class="btn btn-primary" href="{{ route('projects.create') }}">
                <span class="iconify lucide--plus mr-2"></span> New Project
            </a>
        </div>

        <livewire:projects-list />

    </div>
</div>
@endsection