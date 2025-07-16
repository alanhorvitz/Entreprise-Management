@extends('layout.app')

@section('title', 'Users Confirmations Report')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="card bg-base-200 shadow-md mb-6">
        <div class="card-body">
            <h2 class="card-title mb-4">Users Confirmations Report</h2>
            <form method="GET" action="" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Project Filter -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Project</span>
                    </label>
                    <select name="project_id" class="select select-bordered w-full" onchange="this.form.submit()">
                        <option value="">All Projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ ($filters['project_id'] ?? '') == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Custom Date Range -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">From</span>
                    </label>
                    <input type="date" name="date_from" class="input input-bordered w-full" value="{{ $filters['date_from'] ?? '' }}" onchange="this.form.submit()">
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">To</span>
                    </label>
                    <input type="date" name="date_to" class="input input-bordered w-full" value="{{ $filters['date_to'] ?? '' }}" onchange="this.form.submit()">
                </div>
            </form>
        </div>
    </div>
    <div class="overflow-x-auto bg-base-200 rounded-lg shadow">
        <table class="table w-full">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Employee Code</th>
                    <th>Total Confirmations</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData as $row)
                    <tr>
                        <td>{{ $row['name'] }}</td>
                        <td>{{ $row['user'] ? $row['user']->email : '-' }}</td>
                        <td>{{ $row['employee'] ? $row['employee']->employee_code : '-' }}</td>
                        <td><span class="badge badge-primary">{{ $row['total'] }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">No data found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection 