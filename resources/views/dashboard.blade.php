@extends('layout.app')

@section('title', 'Dashboard Home')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Active Projects Card -->
        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-base-content/60 text-sm">Active Projects</p>
                        <div class="mt-1">
                            <p class="text-2xl font-semibold">12</p>
                        </div>
                    </div>
                    <div class="bg-primary/10 p-3 rounded-full">
                        <span class="iconify w-6 h-6 text-primary" data-icon="solar:chart-2-bold-duotone"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tasks Completed Card -->
        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-base-content/60 text-sm">Tasks Completed</p>
                        <div class="mt-1">
                            <p class="text-2xl font-semibold">142</p>
                        </div>
                    </div>
                    <div class="bg-success/10 p-3 rounded-full">
                        <span class="iconify w-6 h-6 text-success" data-icon="solar:check-square-bold-duotone"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Tasks Card -->
        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-base-content/60 text-sm">Pending Tasks</p>
                        <div class="mt-1">
                            <p class="text-2xl font-semibold">38</p>
                        </div>
                    </div>
                    <div class="bg-warning/10 p-3 rounded-full">
                        <span class="iconify w-6 h-6 text-warning" data-icon="solar:clock-circle-bold-duotone"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Members Card -->
        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-base-content/60 text-sm">Team Members</p>
                        <div class="mt-1">
                            <p class="text-2xl font-semibold">24</p>
                        </div>
                    </div>
                    <div class="bg-secondary/10 p-3 rounded-full">
                        <span class="iconify w-6 h-6 text-secondary" data-icon="solar:users-group-rounded-bold-duotone"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects Overview -->
    <div class="mt-6">
        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="card-title">Projects Overview</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Project</th>
                                <th>Status</th>
                                <th>Team</th>
                                <th>Deadline</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-base-200 flex items-center justify-center">
                                        <span class="iconify w-6 h-6" data-icon="solar:laptop-bold-duotone"></span>
                                    </div>
                                    <span>Website Redesign</span>
                                </td>
                                <td><div class="badge badge-success badge-sm">completed</div></td>
                                <td>
                                    <div class="flex -space-x-4">
                                        <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-base-100">
                                            <img src="https://nexus.daisyui.com/images/avatars/4.png" alt="Avatar" class="w-full h-full object-cover" />
                                        </div>
                                        <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-base-100">
                                            <img src="https://nexus.daisyui.com/images/avatars/5.png" alt="Avatar" class="w-full h-full object-cover" />
                                        </div>
                                        <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-base-100">
                                            <img src="https://nexus.daisyui.com/images/avatars/7.png" alt="Avatar" class="w-full h-full object-cover" />
                                        </div>
                                    </div>
                                </td>
                                <td class="text-base-content/70">25 Jun 2024</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Approvals and Tasks -->
    <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pending Approvals -->
        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <h2 class="card-title flex justify-between">
                    <span>Pending Approvals</span>
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-sm btn-ghost">
                            <span class="iconify w-4 h-4" data-icon="solar:alt-arrow-down-bold-duotone"></span>
                        </div>
                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                            <li><a>View All Approvals</a></li>
                            <li><a>Approve All</a></li>
                        </ul>
                    </div>
                </h2>
                
                <div class="space-y-4">
                    <!-- Budget Increase Request -->
                    <div class="card bg-base-200">
                        <div class="card-body p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-medium">Budget Increase Request</h3>
                                    <p class="text-sm text-base-content/70">Mobile App Development</p>
                                    <p class="text-sm mt-2">Additional $5,000 for UI improvements</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <div class="w-6 h-6 bg-accent text-accent-content rounded-full inline-flex items-center justify-center">
                                            <span class="text-xs font-medium">TS</span>
                                        </div>
                                        <span class="text-xs">Requested by Tom Smith</span>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button class="btn btn-sm btn-success">Approve</button>
                                    <button class="btn btn-sm btn-error">Reject</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Timeline Extension -->
                    <div class="card bg-base-200">
                        <div class="card-body p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-medium">Timeline Extension</h3>
                                    <p class="text-sm text-base-content/70">CRM Integration</p>
                                    <p class="text-sm mt-2">2 week extension due to API changes</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <div class="w-6 h-6 bg-primary text-primary-content rounded-full inline-flex items-center justify-center">
                                            <span class="text-xs font-medium">LM</span>
                                        </div>
                                        <span class="text-xs">Requested by Lisa Miller</span>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button class="btn btn-sm btn-success">Approve</button>
                                    <button class="btn btn-sm btn-error">Reject</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tasks Due Soon -->
        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <h2 class="card-title flex justify-between">
                    <span>Tasks Due Soon</span>
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-sm btn-ghost">
                            <span class="iconify w-4 h-4" data-icon="solar:alt-arrow-down-bold-duotone"></span>
                        </div>
                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                            <li><a>View All Tasks</a></li>
                            <li><a>Add New Task</a></li>
                        </ul>
                    </div>
                </h2>
                
                <div class="space-y-4">
                    <!-- Task Item -->
                    <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                        <div>
                            <h3 class="font-medium">Finalize homepage design</h3>
                            <p class="text-sm text-base-content/70">Website Redesign</p>
                        </div>
                        <div class="text-right">
                            <div class="badge badge-error">Today</div>
                            <div class="mt-1 flex justify-end">
                                <div class="w-8 h-8 bg-neutral text-neutral-content rounded-full inline-flex items-center justify-center">
                                    <span class="text-sm font-medium">JD</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
