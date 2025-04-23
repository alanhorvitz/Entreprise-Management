@extends('layout.app')

@section('title', 'Dashboard Home')

@section('content')
<!-- Report Controls -->
<div class="card bg-base-100 shadow-md mb-6">
    <div class="card-body p-4">
        <div class="flex flex-col lg:flex-row justify-between gap-4">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="form-control w-full sm:max-w-xs">
                    <label class="label">
                        <span class="label-text">Date Range</span>
                    </label>
                    <select class="select select-bordered w-full">
                        <option selected>Today (May 16, 2025)</option>
                        <option>Yesterday</option>
                        <option>Last 7 Days</option>
                        <option>Last 30 Days</option>
                        <option>This Month</option>
                        <option>Custom Range</option>
                    </select>
                </div>
                
                <div class="form-control w-full sm:max-w-xs">
                    <label class="label">
                        <span class="label-text">Project</span>
                    </label>
                    <select class="select select-bordered w-full">
                        <option selected>All Projects</option>
                        <option>Website Redesign</option>
                        <option>Mobile App Development</option>
                        <option>Marketing Campaign</option>
                        <option>Product Launch</option>
                        <option>CRM Implementation</option>
                    </select>
                </div>
                
                <div class="form-control w-full sm:max-w-xs">
                    <label class="label">
                        <span class="label-text">Department</span>
                    </label>
                    <select class="select select-bordered w-full">
                        <option selected>All Departments</option>
                        <option>Engineering</option>
                        <option>Design</option>
                        <option>Marketing</option>
                        <option>Product</option>
                        <option>Sales</option>
                    </select>
                </div>
            </div>
            
            <div class="form-control w-full lg:max-w-xs">
                <label class="label">
                    <span class="label-text">Search Assignee</span>
                </label>
                <div class="join w-full">
                    <input class="input input-bordered join-item w-full" placeholder="Search by name..." />
                    <button class="btn join-item">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assignee Reports -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- John Doe Report Card -->
    <div class="card bg-base-100 shadow-md report-card">
        <div class="card-body p-4">
            <div class="flex justify-between items-start">
                <div class="flex items-center gap-3">
                    <div class="avatar">
                        <div class="w-12 rounded-full">
                            <img src="https://daisyui.com/images/stock/photo-1534528741775-53994a69daeb.jpg" alt="John Doe" />
                        </div>
                    </div>
                    <div>
                        <h2 class="card-title">John Doe</h2>
                        <p class="text-sm text-base-content/70">Senior Frontend Developer</p>
                    </div>
                </div>
                <div class="badge badge-primary">Engineering</div>
            </div>
            
            <div class="divider my-2"></div>
            
            <!-- Daily Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="flex flex-col items-center p-2 bg-base-200 rounded-lg">
                    <span class="text-sm text-base-content/70">Completed</span>
                    <span class="text-2xl font-bold text-success">3</span>
                </div>
                <div class="flex flex-col items-center p-2 bg-base-200 rounded-lg">
                    <span class="text-sm text-base-content/70">In Progress</span>
                    <span class="text-2xl font-bold text-info">2</span>
                </div>
            </div>
            
            <div class="card-actions justify-end mt-4">
                <button class="btn btn-sm btn-outline">
                    <i class="fas fa-comment mr-1"></i> Add Comment
                </button>
                <button class="btn btn-sm btn-primary" onClick="openFullReportModal()">
                    <i class="fas fa-eye mr-1"></i> View Full Report
                </button>
            </div>
        </div>
    </div>
    
</div>

<!-- Pagination -->
<div class="flex justify-center mt-8">
    <div class="join">
        <button class="join-item btn btn-sm">«</button>
        <button class="join-item btn btn-sm btn-active">1</button>
        <button class="join-item btn btn-sm">2</button>
        <button class="join-item btn btn-sm">3</button>
        <button class="join-item btn btn-sm">»</button>
    </div>
</div>


<!-- Create Task Modal -->
<dialog id="full-report-modal" class="modal">
    <div class="modal-box max-w-3xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4" id="task-modal-title">full report</h3>
        <!-- Tasks List -->

                <ul class="menu bg-base-200 rounded-box w-full">
                    <li class="task-item priority-high">
                        <div class="flex justify-between items-center w-full">
                            <div>
                                <span class="font-medium">Homepage Redesign</span>
                                <div class="text-xs text-base-content/70">Website Redesign</div>
                            </div>
                            <span class="status-badge status-in-progress">In Progress</span>
                        </div>
                    </li>
                    <li class="task-item priority-medium">
                        <div class="flex justify-between items-center w-full">
                            <div>
                                <span class="font-medium">Navigation Component</span>
                                <div class="text-xs text-base-content/70">Website Redesign</div>
                            </div>
                            <span class="status-badge status-completed">Completed</span>
                        </div>
                    </li>
                    <li class="task-item priority-medium">
                        <div class="flex justify-between items-center w-full">
                            <div>
                                <span class="font-medium">Footer Redesign</span>
                                <div class="text-xs text-base-content/70">Website Redesign</div>
                            </div>
                            <span class="status-badge status-completed">Completed</span>
                        </div>
                    </li>
                    <li class="task-item priority-low">
                        <div class="flex justify-between items-center w-full">
                            <div>
                                <span class="font-medium">Button Component Library</span>
                                <div class="text-xs text-base-content/70">Design System</div>
                            </div>
                            <span class="status-badge status-completed">Completed</span>
                        </div>
                    </li>
                    <li class="task-item priority-high">
                        <div class="flex justify-between items-center w-full">
                            <div>
                                <span class="font-medium">Hero Section Animation</span>
                                <div class="text-xs text-base-content/70">Website Redesign</div>
                            </div>
                            <span class="status-badge status-in-progress">In Progress</span>
                        </div>
                    </li>
                </ul>
        
        <!-- Notes -->
        <div class="mt-4">
            <h3 class="font-medium mb-2">Daily Notes</h3>
            <div class="bg-base-200 p-3 rounded-lg text-sm">
                <p>Completed the navigation component and footer redesign ahead of schedule. Working on the homepage hero section animation which is taking longer than expected due to complex interactions. Will need additional time to complete the animation effects.</p>
            </div>
        </div>
    </div>
</dialog>

<script>
    function openFullReportModal() {

    document.getElementById('full-report-modal').showModal();
    }
</script>
@endsection