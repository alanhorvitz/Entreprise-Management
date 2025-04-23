@extends('layout.app')

@section('title', 'Chats')

@section('content')

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

@endsection
