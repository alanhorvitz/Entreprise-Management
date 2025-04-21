<div role="navigation" aria-label="Navbar" class="flex items-center justify-between px-3" id="layout-topbar">
                        <div class="inline-flex items-center gap-3">
                            <label class="btn btn-square btn-ghost btn-sm" aria-label="Leftmenu toggle" for="layout-sidebar-toggle-trigger"><span class="iconify lucide--menu size-5"></span></label>
                            <dialog id="topbar-search-modal" class="modal p-0">
                                <div class="modal-box p-0">
                                    <div class="input border-base-300 w-full rounded-none border-0 border-b focus:!outline-0 active:!outline-0">
                                        <span class="iconify lucide--search text-base-content/60 size-4.5"></span><input class="grow" placeholder="Search" aria-label="Search" type="search" />
                                        <form method="dialog">
                                            <button class="btn btn-sm btn-circle btn-ghost" aria-label="Close"><span class="iconify lucide--x text-base-content/80 size-4"></span></button>
                                        </form>
                                    </div>
                                    <ul class="menu w-full pt-0">
                                        <li class="menu-title">Actions</li>
                                        <li>
                                            <div>
                                                <span class="iconify lucide--folder-plus size-4.5"></span>
                                                <p class="grow text-sm">Create a new folder</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div>
                                                <span class="iconify lucide--file-plus size-4.5"></span>
                                                <p class="grow text-sm">Upload new document</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div>
                                                <span class="iconify lucide--user-plus size-4.5"></span>
                                                <p class="grow text-sm">Invite to project</p>
                                            </div>
                                        </li>
                                    </ul>
                                    <hr class="border-base-300 h-px" />
                                    <ul class="menu w-full pt-0">
                                        <li class="menu-title">Quick Links</li>
                                        <li>
                                            <div>
                                                <span class="iconify lucide--folders size-4.5"></span>
                                                <p class="grow text-sm">File Manager</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div>
                                                <span class="iconify lucide--user size-4.5"></span>
                                                <p class="grow text-sm">Profile</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div>
                                                <span class="iconify lucide--layout-dashboard size-4.5"></span>
                                                <p class="grow text-sm">Dashboard</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div>
                                                <span class="iconify lucide--help-circle size-4.5"></span>
                                                <p class="grow text-sm">Support</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div>
                                                <span class="iconify lucide--keyboard size-4.5"></span>
                                                <p class="grow text-sm">Keyboard Shortcuts</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <form method="dialog" class="modal-backdrop"><button>close</button></form>
                            </dialog>
                        </div>
                        <div class="inline-flex items-center gap-1.5">
                            <!-- Theme Toggle Button -->
                            <button onclick="toggleTheme()" class="btn btn-circle btn-ghost btn-sm" aria-label="Theme toggle">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-5 h-5 stroke-current">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
                                    ></path>
                                </svg>
                            </button>
                            <div class="dropdown dropdown-bottom sm:dropdown-end max-sm:dropdown-center">
                                <div tabindex="0" role="button" class="btn btn-circle btn-ghost btn-sm" aria-label="Notifications"><span class="iconify lucide--bell size-4.5"></span></div>
                                <div tabindex="0" class="dropdown-content bg-base-100 rounded-box card card-compact mt-5 w-60 p-2 shadow sm:w-84">
                                    <div class="flex items-center justify-between px-2">
                                        <p class="text-base font-medium">Notification</p>
                                        <button tabindex="0" class="btn btn-sm btn-circle btn-ghost" aria-label="Close"><span class="iconify lucide--x size-4"></span></button>
                                    </div>
                                    <div class="flex items-center justify-center">
                                        <div class="badge badge-sm badge-primary badge-soft">Today</div>
                                    </div>
                                    <div class="mt-2">
                                        <div class="rounded-box hover:bg-base-200 flex cursor-pointer gap-3 px-2 py-1.5 transition-all">
                                            <img class="bg-base-200 mask mask-squircle size-10 p-0.5" alt="" src="/images/avatars/4.png" />
                                            <div class="grow">
                                                <p class="text-sm leading-tight">Customer has requested a return for item</p>
                                                <p class="text-base-content/60 text-end text-xs leading-tight">1 Hour ago</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2 flex items-center justify-center">
                                        <div class="badge badge-sm">Previous</div>
                                    </div>
                                    <div class="mt-2">
                                        <div class="rounded-box hover:bg-base-200 flex cursor-pointer gap-3 px-2 py-1.5 transition-all">
                                            <img class="bg-base-200 mask mask-squircle size-10 p-0.5" alt="" src="/images/avatars/1.png" />
                                            <div class="grow">
                                                <p class="text-sm leading-tight">Prepare for the upcoming weekend promotion</p>
                                                <p class="text-base-content/60 text-end text-xs leading-tight">2 Days ago</p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="border-base-300 -mx-2 mt-2" />
                                    <div class="flex items-center justify-between pt-2"><button class="btn btn-sm btn-ghost">Mark as read</button><button class="btn btn-sm btn-soft btn-primary">View All</button></div>
                                </div>
                            </div>
                            <div class="dropdown dropdown-bottom dropdown-end">
                                <div tabindex="0" role="button" class="btn btn-ghost rounded-btn px-1.5">
                                    <div class="flex items-center gap-2">
                                        <div class="avatar">
                                            <div class="bg-base-200 mask mask-squircle w-8"><img alt="Avatar" src="{{ asset('images/image.png') }}" /></div>
                                        </div>
                                        <div class="-space-y-0.5 text-start">
                                            <p class="text-sm">Denish</p>
                                            <p class="text-base-content/60 text-xs">Profile</p>
                                        </div>
                                    </div>
                                </div>
                                <div tabindex="0" class="dropdown-content bg-base-100 rounded-box mt-4 w-44 shadow">
                                    <ul class="menu w-full p-2">
                                        <li>
                                            <a href="/pages/settings" data-discover="true"><span class="iconify lucide--user size-4"></span><span>My Profile</span></a>
                                        </li>
                                        <li>
                                            <a href="/pages/settings" data-discover="true"><span class="iconify lucide--settings size-4"></span><span>Settings</span></a>
                                        </li>
                                        <li>
                                            <a href="/pages/get-help" data-discover="true"><span class="iconify lucide--help-circle size-4"></span><span>Help</span></a>
                                        </li>
                                    </ul>
                                    <hr class="border-base-300" />
                                    <ul class="menu w-full p-2">
                                        <li>
                                            <div><span class="iconify lucide--arrow-left-right size-4"></span><span>Switch Account</span></div>
                                        </li>
                                        <li>
                                            <a class="text-error hover:bg-error/10" href="/auth/login" data-discover="true"><span class="iconify lucide--log-out size-4"></span><span>Logout</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>