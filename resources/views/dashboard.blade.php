@extends('layout.app')

@section('title', 'Dashboard Home')

@section('content')
    <div class="grid tw-cea tw-qk tw-ul">
        <div class="card tw-cr shadow">
            <div class="card-body tw-zda">
                <div class="flex tw-zr tw-pi tw-zda tw-mba">
                    <div>
                        <p class="tw-de tw-rr">Active Projects</p>
                        <div class="tw-aga flex tw-sp tw-zda">
                            <p class="tw-oz tw-um">12</p>
                        </div>
                    </div>
                    <div class="tw-dr tw-ms flex tw-sp tw-tia"><span class="iconify tw-dda lucide--user"></span></div>
                </div>
            </div>
        </div>
        <div class="card tw-cr shadow">
            <div class="card-body tw-zda">
                <div class="flex tw-zr tw-pi tw-zda tw-mba">
                    <div>
                        <p class="tw-de tw-rr">Tasks Completed</p>
                        <div class="tw-aga flex tw-sp tw-zda">
                            <p class="tw-oz tw-um">142</p>
                        </div>
                    </div>
                    <div class="tw-dr tw-ms flex tw-sp tw-tia"><span class="iconify tw-dda lucide--package"></span></div>
                </div>
            </div>
        </div>
        <div class="card tw-cr shadow">
            <div class="card-body tw-zda">
                <div class="flex tw-zr tw-pi tw-zda tw-mba">
                    <div>
                        <p class="tw-de tw-rr">Pending Tasks</p>
                        <div class="tw-aga flex tw-sp tw-zda">
                            <p class="tw-oz tw-um">38</p>
                        </div>
                    </div>
                    <div class="tw-dr tw-ms flex tw-sp tw-tia"><span class="iconify tw-dda lucide--users"></span></div>
                </div>
            </div>
        </div>
        <div class="card tw-cr shadow">
            <div class="card-body tw-zda">
                <div class="flex tw-zr tw-pi tw-zda tw-mba">
                    <div>
                        <p class="tw-de tw-rr">Team Members</p>
                        <div class="tw-aga flex tw-sp tw-zda">
                            <p class="tw-oz tw-um">24</p>
                        </div>
                    </div>
                    <div class="tw-dr tw-ms flex tw-sp tw-tia"><span class="iconify tw-dda lucide--group"></span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-6 tw-qo tw-vj">
        <div aria-label="Card" class="card tw-cr shadow">
            <div class="card-body tw-ria">
                <div class="flex tw-sp tw-aea tw-hha tw-aha">
                    <span class="tw-rr">Projects Overview</span>
                </div>
                <div class="tw-zfa tw-yn">
                    <table class="table tw-wl">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Status</th>
                                <th>Team</th>
                                <th>Deadline</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="flex tw-sp tw-dx truncate">
                                    <img alt="order image" class="mask tw-jn tw-dr tw-iz" src="/images/apps/ecommerce/products/1.jpg" />
                                    <p>Website Redesign</p>
                                </td>
                                <td><div class="badge badge-success badge-sm badge-soft">completed</div></td>
                                <td class="tw-rr">
                                    <div class="avatar-group tw-wo">
                                        <div class="avatar">
                                            <div class="tw-dr tw-bja tw-gq"><img alt="Avatar" src="https://nexus.daisyui.com/images/avatars/4.png" /></div>
                                        </div>
                                        <div class="avatar">
                                            <div class="tw-dr tw-bja tw-gq"><img alt="Avatar" src="https://nexus.daisyui.com/images/avatars/5.png" /></div>
                                        </div>
                                        <div class="avatar">
                                            <div class="tw-dr tw-bja tw-gq"><img alt="Avatar" src="https://nexus.daisyui.com/images/avatars/7.png" /></div>
                                        </div>
                                        <!-- <div class="avatar">
                                            <div class="tw-dr tw-bja tw-gq"><img alt="Avatar" src="https://nexus.daisyui.com/images/avatars/8.png" /></div>
                                        </div> -->
                                    </div>
                                </td>
                                <td class="tw-oba">25 Jun 2024</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <h2 class="card-title flex justify-between">
                    <span>Pending Approvals</span>
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-sm btn-ghost">
                            <i class="fas fa-ellipsis-v"></i>
                        </div>
                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                            <li><a>View All Approvals</a></li>
                            <li><a>Approve All</a></li>
                        </ul>
                    </div>
                </h2>
                
                <div class="space-y-4">
                    <div class="card bg-base-200">
                        <div class="card-body p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-medium">Budget Increase Request</h3>
                                    <p class="text-sm text-base-content/70">Mobile App Development</p>
                                    <p class="text-sm mt-2">Additional $5,000 for UI improvements</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <div class="avatar placeholder">
                                            <div class="text-center bg-accent text-accent-content w-6 rounded-full ">
                                                <span class="text-xs">TS</span>
                                            </div>
                                        </div>
                                        <span class="text-xs">Requested by Tom Smith</span>
                                    </div>
                                </div>
                                <div class="flex gap-2 items-center h-full">
                                    <button class="btn btn-sm btn-success">Approve</button>
                                    <button class="btn btn-sm btn-error">Reject</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card bg-base-200">
                        <div class="card-body p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-medium">Timeline Extension</h3>
                                    <p class="text-sm text-base-content/70">CRM Integration</p>
                                    <p class="text-sm mt-2">2 week extension due to API changes</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <div class="avatar placeholder">
                                            <div class="bg-primary text-primary-content w-6 rounded-full">
                                                <span class="text-xs">LM</span>
                                            </div>
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
                    
                    <div class="card bg-base-200">
                        <div class="card-body p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-medium">New Team Member</h3>
                                    <p class="text-sm text-base-content/70">Data Analytics Platform</p>
                                    <p class="text-sm mt-2">Adding senior data scientist to team</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <div class="avatar placeholder">
                                            <div class="bg-secondary text-secondary-content w-6 rounded-full">
                                                <span class="text-xs">KL</span>
                                            </div>
                                        </div>
                                        <span class="text-xs">Requested by Kevin Lee</span>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button class="btn btn-sm btn-success">Approve</button>
                                    <button class="btn btn-sm btn-error">Reject</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card bg-base-200">
                        <div class="card-body p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-medium">Design Change Request</h3>
                                    <p class="text-sm text-base-content/70">Website Redesign</p>
                                    <p class="text-sm mt-2">Major revision to homepage layout</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <div class="avatar placeholder">
                                            <div class="bg-neutral text-neutral-content w-6 rounded-full">
                                                <span class="text-xs">JD</span>
                                            </div>
                                        </div>
                                        <span class="text-xs">Requested by John Doe</span>
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
        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <h2 class="card-title flex justify-between">
                    <span>Tasks Due Soon</span>
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-sm btn-ghost">
                            <i class="fas fa-ellipsis-v"></i>
                        </div>
                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                            <li><a>View All Tasks</a></li>
                            <li><a>Add New Task</a></li>
                        </ul>
                    </div>
                </h2>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                        <div>
                            <h3 class="font-medium">Finalize homepage design</h3>
                            <p class="text-sm text-base-content/70">Website Redesign</p>
                        </div>
                        <div class="text-right">
                            <div class="badge badge-error">Today</div>
                            <div class="avatar placeholder mt-1">
                                <div class="bg-neutral text-center pt-1 pb-1 text-neutral-content w-8 rounded-full">
                                    <span>JD</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                        <div>
                            <h3 class="font-medium">API integration testing</h3>
                            <p class="text-sm text-base-content/70">Mobile App Development</p>
                        </div>
                        <div class="text-right">
                            <div class="badge badge-warning">Tomorrow</div>
                            <div class="avatar placeholder mt-1">
                                <div class="bg-accent text-accent-content w-8 rounded-full">
                                    <span>TS</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                        <div>
                            <h3 class="font-medium">Prepare quarterly report</h3>
                            <p class="text-sm text-base-content/70">Data Analytics Platform</p>
                        </div>
                        <div class="text-right">
                            <div class="badge badge-primary">2 days</div>
                            <div class="avatar placeholder mt-1">
                                <div class="bg-secondary text-secondary-content w-8 rounded-full">
                                    <span>KL</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                        <div>
                            <h3 class="font-medium">Client presentation</h3>
                            <p class="text-sm text-base-content/70">CRM Integration</p>
                        </div>
                        <div class="text-right">
                            <div class="badge badge-secondary">3 days</div>
                            <div class="avatar placeholder mt-1">
                                <div class="bg-primary text-primary-content w-8 rounded-full">
                                    <span>LM</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                        <div>
                            <h3 class="font-medium">User testing session</h3>
                            <p class="text-sm text-base-content/70">Website Redesign</p>
                        </div>
                        <div class="text-right">
                            <div class="badge">5 days</div>
                            <div class="avatar placeholder mt-1">
                                <div class="bg-info text-info-content w-8 rounded-full">
                                    <span>AM</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
