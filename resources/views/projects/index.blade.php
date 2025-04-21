@extends('layout.app')

@section('title', 'Project Management')

@section('content')

<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <h2 class="card-title mb-4">Projects</h2>
        
        <!-- Search and Filters -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="md:col-span-2">
                <div class="relative">
                    <input type="text" placeholder="Search projects..." class="input input-bordered w-full pl-10">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-base-content/50"></i>
                </div>
            </div>
            
            <div>
                <select class="select select-bordered w-full">
                    <option disabled="" selected="">Status</option>
                    <option>All</option>
                    <option>On Track</option>
                    <option>At Risk</option>
                    <option>Delayed</option>
                    <option>Completed</option>
                </select>
            </div>
            
            <div>
                <select class="select select-bordered w-full">
                    <option disabled="" selected="">Department</option>
                    <option>All</option>
                    <option>Marketing</option>
                    <option>Engineering</option>
                    <option>Product</option>
                    <option>Sales</option>
                    <option>Finance</option>
                </select>
            </div>
        </div>
        
        <!-- Projects Grid View -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Project Card 1 -->
            <div class="card bg-base-200 shadow-md hover:shadow-lg project-card cursor-pointer" onclick="showProjectDetails('project1')">
                <div class="card-body p-4">
                    <div class="flex justify-between items-start">
                        <div class="avatar placeholder">
                            <div class="bg-primary text-primary-content rounded-md w-10">
                                <span>WD</span>
                            </div>
                        </div>
                        <div class="badge badge-success">On Track</div>
                    </div>
                    <h3 class="font-bold text-lg mt-2">Website Redesign</h3>
                    <p class="text-sm text-base-content/70">Marketing</p>
                    <div class="flex justify-between items-center mt-2">
                        <div class="text-sm">Progress</div>
                        <div class="text-sm font-medium">75%</div>
                    </div>
                    <progress class="progress progress-success" value="75" max="100"></progress>
                    <div class="flex justify-between items-center mt-4">
                        <div class="flex -space-x-2">
                            <div class="avatar placeholder">
                                <div class="bg-neutral text-neutral-content w-8 rounded-full">
                                    <span>JD</span>
                                </div>
                            </div>
                            <div class="avatar placeholder">
                                <div class="bg-primary text-primary-content w-8 rounded-full">
                                    <span>AM</span>
                                </div>
                            </div>
                            <div class="avatar placeholder">
                                <div class="bg-secondary text-secondary-content w-8 rounded-full">
                                    <span>+2</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-sm">May 15, 2023</div>
                    </div>
                </div>
            </div>
            
            <!-- Project Card 2 -->
            <div class="card bg-base-200 shadow-md hover:shadow-lg project-card cursor-pointer" onclick="showProjectDetails('project2')">
                <div class="card-body p-4">
                    <div class="flex justify-between items-start">
                        <div class="avatar placeholder">
                            <div class="bg-secondary text-secondary-content rounded-md w-10">
                                <span>MP</span>
                            </div>
                        </div>
                        <div class="badge badge-warning">At Risk</div>
                    </div>
                    <h3 class="font-bold text-lg mt-2">Mobile App Development</h3>
                    <p class="text-sm text-base-content/70">Product</p>
                    <div class="flex justify-between items-center mt-2">
                        <div class="text-sm">Progress</div>
                        <div class="text-sm font-medium">45%</div>
                    </div>
                    <progress class="progress progress-warning" value="45" max="100"></progress>
                    <div class="flex justify-between items-center mt-4">
                        <div class="flex -space-x-2">
                            <div class="avatar placeholder">
                                <div class="bg-accent text-accent-content w-8 rounded-full">
                                    <span>TS</span>
                                </div>
                            </div>
                            <div class="avatar placeholder">
                                <div class="bg-info text-info-content w-8 rounded-full">
                                    <span>RK</span>
                                </div>
                            </div>
                            <div class="avatar placeholder">
                                <div class="bg-secondary text-secondary-content w-8 rounded-full">
                                    <span>+3</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-sm">June 30, 2023</div>
                    </div>
                </div>
            </div>
            
            <!-- Project Card 3 -->
            <div class="card bg-base-200 shadow-md hover:shadow-lg project-card cursor-pointer" onclick="showProjectDetails('project3')">
                <div class="card-body p-4">
                    <div class="flex justify-between items-start">
                        <div class="avatar placeholder">
                            <div class="bg-accent text-accent-content rounded-md w-10">
                                <span>CR</span>
                            </div>
                        </div>
                        <div class="badge badge-error">Delayed</div>
                    </div>
                    <h3 class="font-bold text-lg mt-2">CRM Integration</h3>
                    <p class="text-sm text-base-content/70">Sales</p>
                    <div class="flex justify-between items-center mt-2">
                        <div class="text-sm">Progress</div>
                        <div class="text-sm font-medium">30%</div>
                    </div>
                    <progress class="progress progress-error" value="30" max="100"></progress>
                    <div class="flex justify-between items-center mt-4">
                        <div class="flex -space-x-2">
                            <div class="avatar placeholder">
                                <div class="bg-primary text-primary-content w-8 rounded-full">
                                    <span>LM</span>
                                </div>
                            </div>
                            <div class="avatar placeholder">
                                <div class="bg-neutral text-neutral-content w-8 rounded-full">
                                    <span>PJ</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-sm">April 10, 2023</div>
                    </div>
                </div>
            </div>
            
            <!-- Project Card 4 -->
            <div class="card bg-base-200 shadow-md hover:shadow-lg project-card cursor-pointer" onclick="showProjectDetails('project4')">
                <div class="card-body p-4">
                    <div class="flex justify-between items-start">
                        <div class="avatar placeholder">
                            <div class="bg-info text-info-content rounded-md w-10">
                                <span>DA</span>
                            </div>
                        </div>
                        <div class="badge badge-success">On Track</div>
                    </div>
                    <h3 class="font-bold text-lg mt-2">Data Analytics Platform</h3>
                    <p class="text-sm text-base-content/70">Engineering</p>
                    <div class="flex justify-between items-center mt-2">
                        <div class="text-sm">Progress</div>
                        <div class="text-sm font-medium">85%</div>
                    </div>
                    <progress class="progress progress-success" value="85" max="100"></progress>
                    <div class="flex justify-between items-center mt-4">
                        <div class="flex -space-x-2">
                            <div class="avatar placeholder">
                                <div class="bg-secondary text-secondary-content w-8 rounded-full">
                                    <span>KL</span>
                                </div>
                            </div>
                            <div class="avatar placeholder">
                                <div class="bg-accent text-accent-content w-8 rounded-full">
                                    <span>MN</span>
                                </div>
                            </div>
                            <div class="avatar placeholder">
                                <div class="bg-primary text-primary-content w-8 rounded-full">
                                    <span>+4</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-sm">July 22, 2023</div>
                    </div>
                </div>
            </div>
            
            <!-- Project Card 5 -->
            <div class="card bg-base-200 shadow-md hover:shadow-lg project-card cursor-pointer" onclick="showProjectDetails('project5')">
                <div class="card-body p-4">
                    <div class="flex justify-between items-start">
                        <div class="avatar placeholder">
                            <div class="bg-primary text-primary-content rounded-md w-10">
                                <span>ER</span>
                            </div>
                        </div>
                        <div class="badge">Completed</div>
                    </div>
                    <h3 class="font-bold text-lg mt-2">Employee Recognition Program</h3>
                    <p class="text-sm text-base-content/70">HR</p>
                    <div class="flex justify-between items-center mt-2">
                        <div class="text-sm">Progress</div>
                        <div class="text-sm font-medium">100%</div>
                    </div>
                    <progress class="progress" value="100" max="100"></progress>
                    <div class="flex justify-between items-center mt-4">
                        <div class="flex -space-x-2">
                            <div class="avatar placeholder">
                                <div class="bg-info text-info-content w-8 rounded-full">
                                    <span>RJ</span>
                                </div>
                            </div>
                            <div class="avatar placeholder">
                                <div class="bg-primary text-primary-content w-8 rounded-full">
                                    <span>SL</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-sm">March 5, 2023</div>
                    </div>
                </div>
            </div>
            
            <!-- Project Card 6 -->
            <div class="card bg-base-200 shadow-md hover:shadow-lg project-card cursor-pointer" onclick="showProjectDetails('project6')">
                <div class="card-body p-4">
                    <div class="flex justify-between items-start">
                        <div class="avatar placeholder">
                            <div class="bg-secondary text-secondary-content rounded-md w-10">
                                <span>SC</span>
                            </div>
                        </div>
                        <div class="badge badge-warning">At Risk</div>
                    </div>
                    <h3 class="font-bold text-lg mt-2">Supply Chain Optimization</h3>
                    <p class="text-sm text-base-content/70">Operations</p>
                    <div class="flex justify-between items-center mt-2">
                        <div class="text-sm">Progress</div>
                        <div class="text-sm font-medium">50%</div>
                    </div>
                    <progress class="progress progress-warning" value="50" max="100"></progress>
                    <div class="flex justify-between items-center mt-4">
                        <div class="flex -space-x-2">
                            <div class="avatar placeholder">
                                <div class="bg-accent text-accent-content w-8 rounded-full">
                                    <span>DM</span>
                                </div>
                            </div>
                            <div class="avatar placeholder">
                                <div class="bg-info text-info-content w-8 rounded-full">
                                    <span>BT</span>
                                </div>
                            </div>
                            <div class="avatar placeholder">
                                <div class="bg-secondary text-secondary-content w-8 rounded-full">
                                    <span>+1</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-sm">August 15, 2023</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="flex justify-center mt-6">
            <div class="join">
                <button class="join-item btn btn-sm">«</button>
                <button class="join-item btn btn-sm btn-active">1</button>
                <button class="join-item btn btn-sm">2</button>
                <button class="join-item btn btn-sm">3</button>
                <button class="join-item btn btn-sm">»</button>
            </div>
        </div>
    </div>
</div>

@endsection