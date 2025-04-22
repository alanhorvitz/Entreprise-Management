@extends('layout.app')

@section('title', 'Tasks')

@section('content')
<!-- Filters Section -->
<div class="card bg-base-100 shadow-md mb-6">
    <div class="card-body">
        <div class="flex justify-between items-center mb-5">
            <h2 class="card-title">Tasks</h2>
            
            <button onclick="openCreateTaskModal()" class="btn btn-primary" href="{{ route('tasks.create') }}">
                <span class="iconify lucide--plus mr-2"></span> New Task
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Project</span>
                </label>
                <select class="select select-bordered w-full" id="filter-project">
                    <option value="">All Projects</option>
                    <option>Website Redesign</option>
                    <option>Mobile App Development</option>
                    <option>Marketing Campaign</option>
                    <option>Product Launch</option>
                    <option>CRM Implementation</option>
                </select>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Priority</span>
                </label>
                <select class="select select-bordered w-full" id="filter-priority">
                    <option value="">All Priorities</option>
                    <option>High</option>
                    <option>Medium</option>
                    <option>Low</option>
                </select>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Status</span>
                </label>
                <select class="select select-bordered w-full" id="filter-status">
                    <option value="">All Statuses</option>
                    <option>Not Started</option>
                    <option>In Progress</option>
                    <option>Under Review</option>
                    <option>Completed</option>
                    <option>Blocked</option>
                </select>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Assignee</span>
                </label>
                <select class="select select-bordered w-full" id="filter-assignee">
                    <option value="">All Assignees</option>
                    <option>John Doe</option>
                    <option>Alice Miller</option>
                    <option>Robert Brown</option>
                    <option>Tom Smith</option>
                    <option>Kevin Lee</option>
                    <option>Sarah Johnson</option>
                    <option>David Wilson</option>
                    <option>Emily Clark</option>
                </select>
            </div>
        </div>
        
        <div class="flex justify-between mt-4">
            <div class="form-control max-w-xs">
                <div class="join">
                    <input class="input input-bordered join-item w-full" placeholder="Search tasks..." id="search-tasks" />
                    <button class="btn join-item">
                        <span class="iconify lucide--search"></span>
                    </button>
                </div>
            </div>
            
            <div class="flex gap-2">
                <button class="btn btn-outline btn-sm" id="clear-filters">
                    <i class="fas fa-times mr-1"></i> Clear Filters
                </button>
                <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="btn btn-outline btn-sm">
                        <i class="fas fa-sort mr-1"></i> Sort By
                    </div>
                    <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                        <li><a data-sort="due-date-asc">Due Date (Earliest)</a></li>
                        <li><a data-sort="due-date-desc">Due Date (Latest)</a></li>
                        <li><a data-sort="priority-high">Priority (Highest)</a></li>
                        <li><a data-sort="priority-low">Priority (Lowest)</a></li>
                        <li><a data-sort="name-asc">Name (A-Z)</a></li>
                        <li><a data-sort="name-desc">Name (Z-A)</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tasks Table -->
<div class="overflow-x-auto bg-base-100 rounded-lg shadow-md" id="tasks-container">
    <table class="table table-zebra w-full">
        <thead>
            <tr>
                <th class="w-8">
                    <label>
                        <input type="checkbox" class="checkbox checkbox-sm" />
                    </label>
                </th>
                <th>Task</th>
                <th>Project</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Due Date</th>
                <th>Assignee</th>
                <th class="w-20">Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Task 1 -->
            <tr class="task-row hover" data-task-id="1" data-project="Website Redesign" data-priority="High" data-status="In Progress" data-assignee="John Doe">
                <td>
                    <label>
                        <input type="checkbox" class="checkbox checkbox-sm" />
                    </label>
                </td>
                <td>
                    <div>
                        <div class="font-bold hover:text-primary cursor-pointer" onclick="openTaskDetails(1)">Homepage Redesign</div>
                        <div class="text-xs text-base-content/70 max-w-xs truncate">Redesign the homepage with new branding elements and improve mobile responsiveness.</div>
                    </div>
                </td>
                <td>
                    <span class="">Website Redesign</span>
                </td>
                <td>
                <select class="select select-sm px-1 py-0 appearance-none" style="background-image: none;">
                <option selected>Not Started</option>
                <option>In Progress</option>
                </select>

                </td>
                <td>
                    <span class="badge badge-error">High</span>
                </td>
                <td>May 15, 2025</td>
                <td>
                    <div class="flex items-center gap-2">
                        <div class="avatar">
                            <div class="w-8 rounded-full">
                                <img src="https://daisyui.com/images/stock/photo-1534528741775-53994a69daeb.jpg" alt="John Doe" />
                            </div>
                        </div>
                        <span class="hidden md:inline">John Doe</span>
                    </div>
                </td>
                <td>
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-ghost btn-xs">
                            <span class="iconify lucide--ellipsis-vertical"></span>
                        </div>
                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                            <li><a onclick="openTaskDetails(1)"><span class="iconify lucide--eye"></span>  View Details</a></li>
                            <li><a onclick="openEditTaskModal(1)"><span class="iconify lucide--edit"></span>  Edit Task</a></li>
                            <li><a class="text-error"><span class="iconify lucide--trash-2"></span>  Delete Task</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
            
            <!-- Task 2 -->
            <tr class="task-row hover" data-task-id="2" data-project="Mobile App Development" data-priority="Medium" data-status="Not Started" data-assignee="Alice Miller">
                <td>
                    <label>
                        <input type="checkbox" class="checkbox checkbox-sm" />
                    </label>
                </td>
                <td>
                    <div>
                        <div class="font-bold hover:text-primary cursor-pointer" onclick="openTaskDetails(2)">User Authentication Flow</div>
                        <div class="text-xs text-base-content/70 max-w-xs truncate">Implement user authentication including login, registration, password reset, and social login options.</div>
                    </div>
                </td>
                <td>
                    <span class="">Mobile App Development</span>
                </td>
                <td>
                    <span class="status-badge status-not-started">Not Started</span>
                </td>
                <td>
                    <span class="badge badge-warning">Medium</span>
                </td>
                <td>May 20, 2025</td>
                <td>
                    <div class="flex items-center gap-2">
                        <div class="avatar placeholder">
                            <div class="bg-primary text-primary-content w-8 rounded-full">
                                <span>AM</span>
                            </div>
                        </div>
                        <span class="hidden md:inline">Alice Miller</span>
                    </div>
                </td>

                <td>
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-ghost btn-xs">
                            <i class="fas fa-ellipsis-v"></i> <span class="iconify "></span>
                        </div>
                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                            <li><a onclick="openTaskDetails(2)"><i class="fas fa-eye mr-2"></i> View Details</a></li>
                            <li><a onclick="openEditTaskModal(2)"><i class="fas fa-edit mr-2"></i> Edit Task</a></li>
                            <li><a class="text-error"><i class="fas fa-trash mr-2"></i> Delete Task</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
            
            <!-- Task 3 -->
            <tr class="task-row hover" data-task-id="3" data-project="Marketing Campaign" data-priority="Low" data-status="Completed" data-assignee="Sarah Johnson">
                <td>
                    <label>
                        <input type="checkbox" class="checkbox checkbox-sm" />
                    </label>
                </td>
                <td>
                    <div>
                        <div class="font-bold hover:text-primary cursor-pointer" onclick="openTaskDetails(3)">Social Media Content Calendar</div>
                        <div class="text-xs text-base-content/70 max-w-xs truncate">Create a content calendar for social media posts for the next quarter, including themes, hashtags, and visual assets.</div>
                    </div>
                </td>
                <td>
                    <span class="">Marketing Campaign</span>
                </td>
                <td>
                    <span class="status-badge status-completed">Completed</span>
                </td>
                <td>
                    <span class="badge badge-info">Low</span>
                </td>
                <td>May 5, 2025</td>
                <td>
                    <div class="flex items-center gap-2">
                        <div class="avatar placeholder">
                            <div class="bg-success text-success-content w-8 rounded-full">
                                <span>SJ</span>
                            </div>
                        </div>
                        <span class="hidden md:inline">Sarah Johnson</span>
                    </div>
                </td>
                <td>
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-ghost btn-xs">
                            <i class="fas fa-ellipsis-v"></i>
                        </div>
                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                            <li><a onclick="openTaskDetails(3)"><i class="fas fa-eye mr-2"></i> View Details</a></li>
                            <li><a onclick="openEditTaskModal(3)"><i class="fas fa-edit mr-2"></i> Edit Task</a></li>
                            <li><a class="text-error"><i class="fas fa-trash mr-2"></i> Delete Task</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
            
            <!-- Task 4 -->
            <tr class="task-row hover" data-task-id="4" data-project="Product Launch" data-priority="High" data-status="Blocked" data-assignee="Robert Brown">
                <td>
                    <label>
                        <input type="checkbox" class="checkbox checkbox-sm" />
                    </label>
                </td>
                <td>
                    <div>
                        <div class="font-bold hover:text-primary cursor-pointer" onclick="openTaskDetails(4)">Product Demo Video</div>
                        <div class="text-xs text-base-content/70 max-w-xs truncate">Create a 2-minute product demo video showcasing key features and benefits for the upcoming product launch.</div>
                    </div>
                </td>
                <td>
                    <span class="">Product Launch</span>
                </td>
                <td>
                    <span class="status-badge status-blocked">Blocked</span>
                </td>
                <td>
                    <span class="badge badge-error">High</span>
                </td>
                <td>May 12, 2025</td>
                <td>
                    <div class="flex items-center gap-2">
                        <div class="avatar placeholder">
                            <div class="bg-secondary text-secondary-content w-8 rounded-full">
                                <span>RB</span>
                            </div>
                        </div>
                        <span class="hidden md:inline">Robert Brown</span>
                    </div>
                </td>
                <td>
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-ghost btn-xs">
                            <i class="fas fa-ellipsis-v"></i>
                        </div>
                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                            <li><a onclick="openTaskDetails(4)"><i class="fas fa-eye mr-2"></i> View Details</a></li>
                            <li><a onclick="openEditTaskModal(4)"><i class="fas fa-edit mr-2"></i> Edit Task</a></li>
                            <li><a class="text-error"><i class="fas fa-trash mr-2"></i> Delete Task</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
            
            <!-- Task 5 -->
            <tr class="task-row hover" data-task-id="5" data-project="CRM Implementation" data-priority="Medium" data-status="Under Review" data-assignee="Tom Smith">
                <td>
                    <label>
                        <input type="checkbox" class="checkbox checkbox-sm" />
                    </label>
                </td>
                <td>
                    <div>
                        <div class="font-bold hover:text-primary cursor-pointer" onclick="openTaskDetails(5)">Data Migration Plan</div>
                        <div class="text-xs text-base-content/70 max-w-xs truncate">Develop a comprehensive data migration plan for transferring customer data from the legacy system to the new CRM.</div>
                    </div>
                </td>
                <td>
                    <span class="">CRM Implementation</span>
                </td>
                <td>
                    <span class="status-badge status-review">Under Review</span>
                </td>
                <td>
                    <span class="badge badge-warning">Medium</span>
                </td>
                <td>May 18, 2025</td>
                <td>
                    <div class="flex items-center gap-2">
                        <div class="avatar placeholder">
                            <div class="bg-accent text-accent-content w-8 rounded-full">
                                <span>TS</span>
                            </div>
                        </div>
                        <span class="hidden md:inline">Tom Smith</span>
                    </div>
                </td>
                <td>
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-ghost btn-xs">
                            <i class="fas fa-ellipsis-v"></i>
                        </div>
                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                            <li><a onclick="openTaskDetails(5)"><i class="fas fa-eye mr-2"></i> View Details</a></li>
                            <li><a onclick="openEditTaskModal(5)"><i class="fas fa-edit mr-2"></i> Edit Task</a></li>
                            <li><a class="text-error"><i class="fas fa-trash mr-2"></i> Delete Task</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
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
<dialog id="create-task-modal" class="modal">
    <div class="modal-box max-w-3xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4" id="task-modal-title">Create New Task</h3>
        
        <form id="task-form" class="space-y-4">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text required-field">Task Name</span>
                    </label>
                    <input type="text" id="task-name" placeholder="Enter task name" class="input input-bordered w-full" required />
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text required-field">Project</span>
                    </label>
                    <select id="task-project" class="select select-bordered w-full" required>
                        <option disabled selected value="">Select project</option>
                        <option>Website Redesign</option>
                        <option>Mobile App Development</option>
                        <option>Marketing Campaign</option>
                        <option>Product Launch</option>
                        <option>CRM Implementation</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text required-field">Assignee</span>
                    </label>
                    <select id="task-assignee" class="select select-bordered w-full" required>
                        <option disabled selected value="">Select assignee</option>
                        <option>John Doe</option>
                        <option>Alice Miller</option>
                        <option>Robert Brown</option>
                        <option>Tom Smith</option>
                        <option>Kevin Lee</option>
                        <option>Sarah Johnson</option>
                        <option>David Wilson</option>
                        <option>Emily Clark</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text required-field">Due Date</span>
                    </label>
                    <input type="date" id="task-due-date" class="input input-bordered w-full" required />
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text required-field">Priority</span>
                    </label>
                    <select id="task-priority" class="select select-bordered w-full" required>
                        <option disabled selected value="">Select priority</option>
                        <option>High</option>
                        <option>Medium</option>
                        <option>Low</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text required-field">Status</span>
                    </label>
                    <select id="task-status" class="select select-bordered w-full" required>
                        <option disabled selected value="">Select status</option>
                        <option>Not Started</option>
                        <option>In Progress</option>
                        <option>Under Review</option>
                        <option>Completed</option>
                        <option>Blocked</option>
                    </select>
                </div>
            </div>
            
            <div class="form-control flex flex-col w-full">
                <label class="label">
                    <span class="label-text required-field">Description</span>
                </label>
                <textarea id="task-description" class="textarea textarea-bordered h-24 w-full" placeholder="Enter task description" required></textarea>
            </div>
            

                <div class="form-control w-full mt-4">
                    <label class="label">
                        <span class="label-text">Assignee</span>
                    </label>
                    <div class="bg-base-200 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                            <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-base-300 rounded-md">
                                <input type="checkbox" class="checkbox checkbox-sm" />
                                <div class="flex items-center gap-2">
                                    <div class="avatar placeholder">
                                        <div class="bg-neutral text-neutral-content w-8 rounded-full">
                                            <span>JD</span>
                                        </div>
                                    </div>
                                    <span>John Doe</span>
                                </div>
                            </label>
                            
                            <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-base-300 rounded-md">
                                <input type="checkbox" class="checkbox checkbox-sm" />
                                <div class="flex items-center gap-2">
                                    <div class="avatar placeholder">
                                        <div class="bg-primary text-primary-content w-8 rounded-full">
                                            <span>AM</span>
                                        </div>
                                    </div>
                                    <span>Alice Miller</span>
                                </div>
                            </label>
                            
                            <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-base-300 rounded-md">
                                <input type="checkbox" class="checkbox checkbox-sm" />
                                <div class="flex items-center gap-2">
                                    <div class="avatar placeholder">
                                        <div class="bg-secondary text-secondary-content w-8 rounded-full">
                                            <span>RB</span>
                                        </div>
                                    </div>
                                    <span>Robert Brown</span>
                                </div>
                            </label>
                            
                            <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-base-300 rounded-md">
                                <input type="checkbox" class="checkbox checkbox-sm" />
                                <div class="flex items-center gap-2">
                                    <div class="avatar placeholder">
                                        <div class="bg-accent text-accent-content w-8 rounded-full">
                                            <span>TS</span>
                                        </div>
                                    </div>
                                    <span>Tom Smith</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            <div class="modal-action">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('create-task-modal').close()">Cancel</button>
                <button type="submit" class="btn btn-primary" id="save-task-btn">Save Task</button>
            </div>
        </form>
    </div>
</dialog>

    
    
    <!-- Task Details Modal -->
    <dialog id="task-details-modal" class="modal">
        <div class="modal-box max-w-5xl px-8 pt-12">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <div id="task-details-content">
                <!-- Task details will be loaded here -->
                <div class="flex justify-between items-start mb-4">
                    <h3 class="font-bold text-xl" id="detail-task-name">Homepage Redesign</h3>
                    <div class="flex gap-2">
                        <span class="badge badge-error" id="detail-task-priority">High Priority</span>
                        <span class="status-badge status-in-progress" id="detail-task-status">In Progress</span>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="flex flex-col">
                        <span class="text-sm text-base-content/70">Project</span>
                        <span class="font-medium" id="detail-task-project">Website Redesign</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm text-base-content/70">Assignee</span>
                        <div class="flex items-center gap-2">
                            <div class="avatar">
                                <div class="w-6 rounded-full">
                                    <img src="https://daisyui.com/images/stock/photo-1534528741775-53994a69daeb.jpg" alt="John Doe" />
                                </div>
                            </div>
                            <span class="font-medium" id="detail-task-assignee">John Doe</span>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm text-base-content/70">Due Date</span>
                        <span class="font-medium" id="detail-task-due-date">May 15, 2025</span>
                    </div>
                </div>
                
                <div class="mb-6">
                    <h4 class="font-semibold mb-2">Description</h4>
                    <p class="text-base-content/80" id="detail-task-description">
                        Redesign the homepage with new branding elements and improve mobile responsiveness. This includes updating the hero section, feature highlights, testimonials, and call-to-action areas. Ensure the design is consistent with the new brand guidelines and optimized for all device sizes.
                    </p>
                </div>
                
                <div class="divider"></div>
                
                <!-- Comments Section -->
                <div>
                    <h4 class="font-semibold mb-4">Comments</h4>
                    
                    <div class="space-y-4 mb-6" id="comments-container">
                        <!-- Comment 1 -->
                        <div class="flex gap-3">
                            <div class="avatar placeholder">
                                <div class="w-10 rounded-full">
                                    <img src="https://daisyui.com/images/stock/photo-1534528741775-53994a69daeb.jpg" alt="John Doe" />
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-center mb-1">
                                    <div class="font-medium">John Doe</div>
                                    <div class="text-xs text-base-content/60">May 2, 2025 at 10:23 AM</div>
                                </div>
                                <div class="bg-base-200 p-3 rounded-lg">
                                    <p>I've completed the initial wireframes for the homepage. Please review and provide feedback.</p>
                                </div>
                                <div class="flex gap-2 mt-1 text-xs">
                                    <button class="hover:text-primary">Reply</button>
                                    <button class="hover:text-primary">Edit</button>
                                    <button class="hover:text-error">Delete</button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Comment 2 -->
                        <div class="flex gap-3">
                            <div class="avatar">
                                <div class="bg-neutral text-neutral-content w-10 rounded-full">
                                    <span>AM</span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-center mb-1">
                                    <div class="font-medium">Alice Miller</div>
                                    <div class="text-xs text-base-content/60">May 3, 2025 at 2:45 PM</div>
                                </div>
                                <div class="bg-base-200 p-3 rounded-lg">
                                    <p>The wireframes look good! I suggest making the CTA button more prominent and adding a section for customer testimonials.</p>
                                </div>
                                <div class="flex gap-2 mt-1 text-xs">
                                    <button class="hover:text-primary">Reply</button>
                                    <button class="hover:text-primary">Edit</button>
                                    <button class="hover:text-error">Delete</button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Comment 3 with attachment -->
                        <div class="flex gap-3">
                            <div class="avatar placeholder">
                                <div class="bg-success text-success-content w-10 rounded-full">
                                    <span>SJ</span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-center mb-1">
                                    <div class="font-medium">Sarah Johnson</div>
                                    <div class="text-xs text-base-content/60">May 4, 2025 at 9:15 AM</div>
                                </div>
                                <div class="bg-base-200 p-3 rounded-lg">
                                    <p class="mb-2">I've updated the brand guidelines with new color schemes. Please use these for the homepage redesign.</p>
                                    <div class="flex items-center gap-2 p-2 bg-base-300 rounded">
                                        <i class="fas fa-file-pdf text-error"></i>
                                        <span class="text-sm">updated-brand-guidelines.pdf</span>
                                        <a href="#" class="ml-auto text-xs text-primary">Download</a>
                                    </div>
                                </div>
                                <div class="flex gap-2 mt-1 text-xs">
                                    <button class="hover:text-primary">Reply</button>
                                    <button class="hover:text-primary">Edit</button>
                                    <button class="hover:text-error">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Add Comment Form -->
                    <form id="comment-form" class="flex gap-3">
                        <div class="avatar">
                            <div class="w-10 rounded-full">
                                <img src="https://daisyui.com/images/stock/photo-1534528741775-53994a69daeb.jpg" alt="Current User" />
                            </div>
                        </div>
                        <div class="flex-1">
                            <textarea class="textarea textarea-bordered w-full" placeholder="Add a comment..." rows="3"></textarea>
                            <div class="flex justify-between mt-2">
                                <div>
                                    <button type="button" class="btn btn-sm btn-ghost">
                                        <i class="fas fa-paperclip"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-ghost">
                                        <i class="fas fa-at"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-ghost">
                                        <i class="fas fa-smile"></i>
                                    </button>
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary">Post Comment</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </dialog>
    
    <script>
        // Toggle between list and grid views
        document.getElementById('view-list').addEventListener('click', function() {
            this.classList.add('active');
            document.getElementById('view-grid').classList.remove('active');
            // Add logic to change view
        });
        
        document.getElementById('view-grid').addEventListener('click', function() {
            this.classList.add('active');
            document.getElementById('view-list').classList.remove('active');
            // Add logic to change view
        });
        
        // Clear filters
        document.getElementById('clear-filters').addEventListener('click', function() {
            document.getElementById('filter-project').value = '';
            document.getElementById('filter-priority').value = '';
            document.getElementById('filter-status').value = '';
            document.getElementById('filter-assignee').value = '';
            document.getElementById('search-tasks').value = '';
            // Reset the task list
            filterTasks();
        });
        
        // Filter tasks based on selected criteria
        function filterTasks() {
            const project = document.getElementById('filter-project').value;
            const priority = document.getElementById('filter-priority').value;
            const status = document.getElementById('filter-status').value;
            const assignee = document.getElementById('filter-assignee').value;
            const search = document.getElementById('search-tasks').value.toLowerCase();
            
            const tasks = document.querySelectorAll('.task-row');
            
            tasks.forEach(task => {
                const taskProject = task.getAttribute('data-project');
                const taskPriority = task.getAttribute('data-priority');
                const taskStatus = task.getAttribute('data-status');
                const taskAssignee = task.getAttribute('data-assignee');
                const taskName = task.querySelector('.font-bold').textContent.toLowerCase();
                const taskDescription = task.querySelector('.text-xs.text-base-content\/70').textContent.toLowerCase();
                
                let show = true;
                
                if (project && taskProject !== project) show = false;
                if (priority && taskPriority !== priority) show = false;
                if (status && taskStatus !== status) show = false;
                if (assignee && taskAssignee !== assignee) show = false;
                if (search && !taskName.includes(search) && !taskDescription.includes(search)) show = false;
                
                task.style.display = show ? '' : 'none';
            });
        }
        
        // Add event listeners to filter controls
        document.getElementById('filter-project').addEventListener('change', filterTasks);
        document.getElementById('filter-priority').addEventListener('change', filterTasks);
        document.getElementById('filter-status').addEventListener('change', filterTasks);
        document.getElementById('filter-assignee').addEventListener('change', filterTasks);
        document.getElementById('search-tasks').addEventListener('input', filterTasks);
        
        // Open task details modal
        function openTaskDetails(taskId) {
            // In a real application, you would fetch task details from the server
            // For now, we'll just show the modal with sample data
            document.getElementById('task-details-modal').showModal();
        }
        
        // Open edit task modal
        function openEditTaskModal(taskId) {
            // Set modal title to "Edit Task"
            document.getElementById('task-modal-title').textContent = 'Edit Task';
            
            // In a real application, you would fetch task data and populate the form
            // For now, we'll just show the modal with empty fields
            document.getElementById('task-id').value = taskId;
            
            // Show the modal
            document.getElementById('create-task-modal').showModal();
        }

        function openCreateTaskModal() {
            // Set modal title to "Edit Task"
            document.getElementById('task-modal-title').textContent = 'Create Task';
            
            // In a real application, you would fetch task data and populate the form
            // For now, we'll just show the modal with empty fields
            // document.getElementById('task-id').value = taskId;
            
            // Show the modal
            document.getElementById('create-task-modal').showModal();
        }
        
        // Handle task form submission
        document.getElementById('task-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // In a real application, you would save the task data to the server
            
            // Close the modal
            document.getElementById('create-task-modal').close();
            
            // Show success message
            alert('Task saved successfully!');
            
            // Reset the form
            this.reset();
            document.getElementById('task-modal-title').textContent = 'Create New Task';
        });
        
        // Handle comment form submission
        document.getElementById('comment-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // In a real application, you would save the comment to the server
            
            // Clear the textarea
            this.querySelector('textarea').value = '';
            
            // Show success message or update the comments list
            alert('Comment added successfully!');
        });
        
        // Toggle active class for sidebar items
        document.querySelectorAll('.sidebar-item').forEach(item => {
            item.addEventListener('click', function() {
                // Remove active class from all items
                document.querySelectorAll('.sidebar-item').forEach(i => {
                    i.classList.remove('active');
                });
                
                // Add active class to clicked item
                this.classList.add('active');
            });
        });
    </script>
@endsection