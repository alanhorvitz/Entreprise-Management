<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="modern-dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        // Check for saved theme preference, otherwise use system preference
        function getTheme() {
            if (localStorage.getItem('theme')) {
                return localStorage.getItem('theme');
            }
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'modern-dark' : 'modern-light';
        }

        // Set theme on page load
        document.documentElement.setAttribute('data-theme', getTheme());

        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'modern-light' ? 'modern-dark' : 'modern-light';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        }
    </script>
</head>
<body class="min-h-screen bg-base-200">
    <!-- Navbar -->
    <div class="navbar bg-base-300">
        <div class="flex-1">
            <a class="btn btn-ghost text-xl">Enterprise Dashboard</a>
        </div>
        <div class="flex-none gap-2">
            <!-- Theme Toggle Button -->
            <button onclick="toggleTheme()" class="btn btn-ghost btn-circle">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 theme-controller" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path class="sun" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    <path class="moon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>
            <div class="form-control">
                <input type="text" placeholder="Search" class="input input-bordered w-24 md:w-auto" />
            </div>
            <div class="dropdown dropdown-end">
                <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                    <div class="w-10 rounded-full">
                        <img src="https://ui-avatars.com/api/?name=User" alt="avatar"/>
                    </div>
                </label>
                <ul tabindex="0" class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-300 rounded-box w-52">
                    <li><a>Profile</a></li>
                    <li><a>Settings</a></li>
                    <li><a>Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="drawer lg:drawer-open">
        <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col p-4">
            <!-- Page content here -->
            <label for="my-drawer-2" class="btn btn-primary drawer-button lg:hidden mb-4">Open Menu</label>
            
            <!-- Dashboard Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Stats Cards -->
                <div class="stats shadow bg-primary text-primary-content">
                    <div class="stat">
                        <div class="stat-title">Total Revenue</div>
                        <div class="stat-value">$25.6K</div>
                        <div class="stat-desc">21% more than last month</div>
                    </div>
                </div>

                <div class="stats shadow bg-accent text-accent-content">
                    <div class="stat">
                        <div class="stat-title">New Users</div>
                        <div class="stat-value">4,200</div>
                        <div class="stat-desc">↗︎ 400 (22%)</div>
                    </div>
                </div>

                <div class="stats shadow bg-secondary text-secondary-content">
                    <div class="stat">
                        <div class="stat-title">Active Projects</div>
                        <div class="stat-value">85</div>
                        <div class="stat-desc">↗︎ 5 new this week</div>
                    </div>
                </div>

                <!-- Recent Activity Card -->
                <div class="card bg-base-300 shadow-xl col-span-full lg:col-span-2">
                    <div class="card-body">
                        <h2 class="card-title">Recent Activity</h2>
                        <div class="overflow-x-auto">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Activity</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>2024-03-20</td>
                                        <td>Project Alpha launched</td>
                                        <td><div class="badge badge-success">Completed</div></td>
                                    </tr>
                                    <tr>
                                        <td>2024-03-19</td>
                                        <td>Team meeting scheduled</td>
                                        <td><div class="badge badge-warning">Pending</div></td>
                                    </tr>
                                    <tr>
                                        <td>2024-03-18</td>
                                        <td>New feature deployment</td>
                                        <td><div class="badge badge-info">In Progress</div></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="card bg-base-300 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">Quick Actions</h2>
                        <div class="flex flex-col gap-2">
                            <button class="btn btn-primary">Create Project</button>
                            <button class="btn btn-secondary">Add Team Member</button>
                            <button class="btn btn-accent">Generate Report</button>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        <div class="drawer-side">
            <label for="my-drawer-2" class="drawer-overlay"></label> 
            <ul class="menu p-4 w-80 min-h-full bg-base-300 text-base-content">
                <!-- Sidebar content here -->
                <li class="mb-2"><a class="active">Dashboard</a></li>
                <li class="mb-2"><a>Projects</a></li>
                <li class="mb-2"><a>Team</a></li>
                <li class="mb-2"><a>Calendar</a></li>
                <li class="mb-2"><a>Documents</a></li>
                <li class="mb-2"><a>Reports</a></li>
                <li class="mb-2"><a>Settings</a></li>
            </ul>
        </div>
    </div>
</body>
</html>
