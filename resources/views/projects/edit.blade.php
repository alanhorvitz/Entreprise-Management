@extends('layout.app')

@section('title', 'Edit Project')

@section('content')
    @livewire('projects.edit', ['project' => $project])
@endsection 