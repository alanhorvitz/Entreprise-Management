@extends('layout.app')

@section('title', 'Project Management')

@section('content')
                <!-- Project Creation Form -->
                <form id="create-project-form" class="space-y-6 max-w-5xl mx-auto">
                    <!-- Basic Information Section -->
                    <div class="card bg-base-100 shadow-xl form-section">
                        <div class="card-body">
                            <h2 class="card-title text-xl flex items-center gap-2">
                                <span class="iconify lucide--info"></span> Basic Information
                            </h2>
                            <p class="text-sm text-base-content/70 mb-4">Enter the essential details about your project</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="form-control w-full">
                                    <label class="label">
                                        <span class="label-text required-field">Project Name</span>
                                    </label>
                                    <input type="text" placeholder="Enter project name" class="input input-bordered w-full" required />
                                    <label class="label">
                                        <span class="label-text-alt">Choose a clear, descriptive name</span>
                                    </label>
                                </div>
                                
                                <div class="form-control w-full">
                                    <label class="label">
                                        <span class="label-text required-field">Department</span>
                                    </label>
                                    <select class="select select-bordered w-full" required>
                                        <option disabled selected value="">Select department</option>
                                        <option>Marketing</option>
                                        <option>Engineering</option>
                                        <option>Product</option>
                                        <option>Sales</option>
                                        <option>Finance</option>
                                        <option>HR</option>
                                        <option>Operations</option>
                                    </select>
                                </div>
                                
                                <div class="form-control w-full">
                                    <label class="label">
                                        <span class="label-text required-field">Start Date</span>
                                    </label>
                                    <input type="date" class="input input-bordered w-full" required />
                                </div>
                                
                                <div class="form-control w-full">
                                    <label class="label">
                                        <span class="label-text required-field">Deadline</span>
                                    </label>
                                    <input type="date" class="input input-bordered w-full" required />
                                </div>
                                
                                <div class="form-control w-full">
                                    <label class="label">
                                        <span class="label-text required-field">Status</span>
                                    </label>
                                    <select class="select select-bordered w-full" required>
                                        <option disabled selected value="">Select status</option>
                                        <option>Not Started</option>
                                        <option>On Track</option>
                                        <option>At Risk</option>
                                        <option>Delayed</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-control w-full mt-4">
                                <label class="label">
                                    <span class="label-text required-field">Project Description</span>
                                </label>
                                <textarea class="textarea textarea-bordered h-32 w-full" placeholder="Provide a detailed description of the project objectives, scope, and expected outcomes" required></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Team & Resources Section -->
                    <div class="card bg-base-100 shadow-xl form-section">
                        <div class="card-body">
                            <h2 class="card-title text-xl flex items-center gap-2">
                                <span class="iconify lucide--users"></span> Team & Resources
                            </h2>
                            <p class="text-sm text-base-content/70 mb-4">Assign project members and allocate resources</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="form-control w-full">
                                    <label class="label">
                                        <span class="label-text required-field">Project Manager</span>
                                    </label>
                                    <select class="select select-bordered w-full" required>
                                        <option disabled selected value="">Select project manager</option>
                                        <option>John Doe</option>
                                        <option>Lisa Miller</option>
                                        <option>Tom Smith</option>
                                        <option>Kevin Lee</option>
                                        <option>Sarah Johnson</option>
                                    </select>
                                </div>
                                <div class="form-control w-full">
                                    <label class="label">
                                        <span class="label-text required-field">Team Manager</span>
                                    </label>
                                    <select class="select select-bordered w-full" required>
                                        <option disabled selected value="">Select team manager</option>
                                        <option>John Doe</option>
                                        <option>Lisa Miller</option>
                                        <option>Tom Smith</option>
                                        <option>Kevin Lee</option>
                                        <option>Sarah Johnson</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-control w-full">
                            <div class="form-control mt-4 w-full">
                                    <label class="label">
                                        <span class="label-text 2">Budget</span>
                                    </label>
                                    <div class="join w-full">
                                        <input type="number" placeholder="0.00" min="0" step="0.01" class="input input-bordered join-item w-full" />
                                        <span class="join-item flex items-center px-3 bg-base-200 border border-r-0 border-base-300">DH</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-control w-full mt-4">
                                <label class="label">
                                    <span class="label-text">Team Members</span>
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
                                        
                                        <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-base-300 rounded-md">
                                            <input type="checkbox" class="checkbox checkbox-sm" />
                                            <div class="flex items-center gap-2">
                                                <div class="avatar placeholder">
                                                    <div class="bg-info text-info-content w-8 rounded-full">
                                                        <span>KL</span>
                                                    </div>
                                                </div>
                                                <span>Kevin Lee</span>
                                            </div>
                                        </label>
                                        
                                        <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-base-300 rounded-md">
                                            <input type="checkbox" class="checkbox checkbox-sm" />
                                            <div class="flex items-center gap-2">
                                                <div class="avatar placeholder">
                                                    <div class="bg-success text-success-content w-8 rounded-full">
                                                        <span>SJ</span>
                                                    </div>
                                                </div>
                                                <span>Sarah Johnson</span>
                                            </div>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-base-300 rounded-md">
                                            <input type="checkbox" class="checkbox checkbox-sm" />
                                            <div class="flex items-center gap-2">
                                                <div class="avatar placeholder">
                                                    <div class="bg-warning text-warning-content w-8 rounded-full">
                                                        <span>DW</span>
                                                    </div>
                                                </div>
                                                <span>David Wilson</span>
                                            </div>
                                        </label>
                                        
                                        <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-base-300 rounded-md">
                                            <input type="checkbox" class="checkbox checkbox-sm" />
                                            <div class="flex items-center gap-2">
                                                <div class="avatar placeholder">
                                                    <div class="bg-error text-error-content w-8 rounded-full">
                                                        <span>EC</span>
                                                    </div>
                                                </div>
                                                <span>Emily Clark</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    

                    
                    <!-- Additional Settings Section -->
                    <div class="card bg-base-100 shadow-xl form-section">
                        <div class="card-body">
                            <h2 class="card-title text-xl flex items-center gap-2">
                                <span class="iconify lucide--cog"></span> Additional Settings
                            </h2>
                            <p class="text-sm text-base-content/70 mb-4">Configure additional project settings</p>
                            <div class="form-control w-full mt-4">
                                <label class="label cursor-pointer justify-start gap-2">
                                    <input type="checkbox" class="checkbox checkbox-primary" />
                                    <span class="label-text">Send notifications to team members</span>
                                </label>
                            </div>
                            
                            <div class="form-control w-full">
                                <label class="label cursor-pointer justify-start gap-2">
                                    <input type="checkbox" class="checkbox checkbox-primary" />
                                    <span class="label-text">Add to featured projects</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="flex justify-end gap-4 sticky bottom-0 bg-base-200 p-4 shadow-lg rounded-t-lg">
                        <a href="projects.html" class="btn btn-outline">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Project</button>
                    </div>
                </form>
@endsection
