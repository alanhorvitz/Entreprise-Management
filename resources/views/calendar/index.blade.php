@extends('layout.app')

@section('title', 'Dashboard Home')

@section('content')
<!-- Calendar Controls -->
<div class="card bg-base-100 shadow-md mb-6">
    <div class="card-body p-4">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2">
                <button class="btn btn-square btn-outline" id="prev-btn">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="btn btn-outline" id="today-btn">Today</button>
                <button class="btn btn-square btn-outline" id="next-btn">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <h2 class="text-xl font-semibold ml-2" id="current-date">May 2025</h2>
            </div>
            
            <div class="flex flex-wrap gap-2">
                <div role="tablist" class="tabs tabs-boxed">
                    <a role="tab" class="tab tab-active" data-view="month">Month</a>
                    <a role="tab" class="tab" data-view="week">Week</a>
                    <a role="tab" class="tab" data-view="day">Day</a>
                </div>
                
                <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="btn btn-outline btn-sm">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </div>
                    <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                        <li class="menu-title">Projects</li>
                        <li>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="checkbox checkbox-sm" checked />
                                <span>Website Redesign</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="checkbox checkbox-sm" checked />
                                <span>Mobile App Development</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="checkbox checkbox-sm" checked />
                                <span>Marketing Campaign</span>
                            </label>
                        </li>
                        <li class="menu-title">Priority</li>
                        <li>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="checkbox checkbox-sm" checked />
                                <span>High</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="checkbox checkbox-sm" checked />
                                <span>Medium</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="checkbox checkbox-sm" checked />
                                <span>Low</span>
                            </label>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Month View (Default) -->
<div id="month-view" class="card bg-base-100 shadow-md">
    <div class="card-body p-4">
        <!-- Weekday Headers -->
        <div class="grid grid-cols-7 gap-1 mb-2">
            <div class="text-center font-semibold">Sun</div>
            <div class="text-center font-semibold">Mon</div>
            <div class="text-center font-semibold">Tue</div>
            <div class="text-center font-semibold">Wed</div>
            <div class="text-center font-semibold">Thu</div>
            <div class="text-center font-semibold">Fri</div>
            <div class="text-center font-semibold">Sat</div>
        </div>
        
        <!-- Calendar Grid -->
        <div class="grid grid-cols-7 gap-1">
            <!-- Previous Month Days (inactive) -->
            <div class="calendar-cell p-2 border rounded-lg inactive" data-date="2025-04-27">
                <div class="text-right mb-1">27</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg inactive" data-date="2025-04-28">
                <div class="text-right mb-1">28</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg inactive" data-date="2025-04-29">
                <div class="text-right mb-1">29</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg inactive" data-date="2025-04-30">
                <div class="text-right mb-1">30</div>
            </div>
            
            <!-- Current Month Days -->
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-01">
                <div class="text-right mb-1">1</div>
                <div class="task-pill priority-medium status-in-progress" onclick="openTaskDetails(3)">
                    Content Calendar
                </div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-02">
                <div class="text-right mb-1">2</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-03">
                <div class="text-right mb-1">3</div>
            </div>
            
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-04">
                <div class="text-right mb-1">4</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-05">
                <div class="text-right mb-1">5</div>
                <div class="task-pill priority-low status-completed" onclick="openTaskDetails(3)">
                    Social Media Calendar
                </div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-06">
                <div class="text-right mb-1">6</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-07">
                <div class="text-right mb-1">7</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-08">
                <div class="text-right mb-1">8</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-09">
                <div class="text-right mb-1">9</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-10">
                <div class="text-right mb-1">10</div>
            </div>
            
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-11">
                <div class="text-right mb-1">11</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-12">
                <div class="text-right mb-1">12</div>
                <div class="task-pill priority-high status-blocked" onclick="openTaskDetails(4)">
                    Product Demo Video
                </div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-13">
                <div class="text-right mb-1">13</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-14">
                <div class="text-right mb-1">14</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-15">
                <div class="text-right mb-1">15</div>
                <div class="task-pill priority-high status-in-progress" onclick="openTaskDetails(1)">
                    Homepage Redesign
                </div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg bg-base-200" data-date="2025-05-16">
                <div class="text-right mb-1 font-bold">16</div>
                <div class="text-xs text-center text-base-content/70">Today</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-17">
                <div class="text-right mb-1">17</div>
            </div>
            
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-18">
                <div class="text-right mb-1">18</div>
                <div class="task-pill priority-medium status-review" onclick="openTaskDetails(5)">
                    Data Migration Plan
                </div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-19">
                <div class="text-right mb-1">19</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-20">
                <div class="text-right mb-1">20</div>
                <div class="task-pill priority-medium status-not-started" onclick="openTaskDetails(2)">
                    User Authentication
                </div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-21">
                <div class="text-right mb-1">21</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-22">
                <div class="text-right mb-1">22</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-23">
                <div class="text-right mb-1">23</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-24">
                <div class="text-right mb-1">24</div>
            </div>
            
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-25">
                <div class="text-right mb-1">25</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-26">
                <div class="text-right mb-1">26</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-27">
                <div class="text-right mb-1">27</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-28">
                <div class="text-right mb-1">28</div>
                <div class="task-pill priority-medium" onclick="openQuickTaskModal('2025-05-28')">
                    Team Meeting
                </div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-29">
                <div class="text-right mb-1">29</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-30">
                <div class="text-right mb-1">30</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg" data-date="2025-05-31">
                <div class="text-right mb-1">31</div>
            </div>
            
            <!-- Next Month Days (inactive) -->
            <div class="calendar-cell p-2 border rounded-lg inactive" data-date="2025-06-01">
                <div class="text-right mb-1">1</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg inactive" data-date="2025-06-02">
                <div class="text-right mb-1">2</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg inactive" data-date="2025-06-03">
                <div class="text-right mb-1">3</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg inactive" data-date="2025-06-04">
                <div class="text-right mb-1">4</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg inactive" data-date="2025-06-05">
                <div class="text-right mb-1">5</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg inactive" data-date="2025-06-06">
                <div class="text-right mb-1">6</div>
            </div>
            <div class="calendar-cell p-2 border rounded-lg inactive" data-date="2025-06-07">
                <div class="text-right mb-1">7</div>
            </div>
        </div>
    </div>
</div>

<!-- Week View (Hidden by default) -->
<div id="week-view" class="card bg-base-100 shadow-md hidden">
    <div class="card-body p-4">
        <div class="week-view-container">
            <div class="flex">
                <!-- Time Column -->
                <div class="w-16 flex-shrink-0">
                    <div class="h-12"></div> <!-- Header spacer -->
                    <div class="text-xs text-right pr-2 h-12">8:00 AM</div>
                    <div class="text-xs text-right pr-2 h-12">9:00 AM</div>
                    <div class="text-xs text-right pr-2 h-12">10:00 AM</div>
                    <div class="text-xs text-right pr-2 h-12">11:00 AM</div>
                    <div class="text-xs text-right pr-2 h-12">12:00 PM</div>
                    <div class="text-xs text-right pr-2 h-12">1:00 PM</div>
                    <div class="text-xs text-right pr-2 h-12">2:00 PM</div>
                    <div class="text-xs text-right pr-2 h-12">3:00 PM</div>
                    <div class="text-xs text-right pr-2 h-12">4:00 PM</div>
                    <div class="text-xs text-right pr-2 h-12">5:00 PM</div>
                </div>
                
                <!-- Days of the Week -->
                <div class="flex flex-1 min-w-0">
                    <!-- Sunday -->
                    <div class="week-view-day border-l">
                        <div class="text-center font-semibold p-2 border-b">
                            Sun<br>May 11
                        </div>
                        <div class="relative">
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                        </div>
                    </div>
                    
                    <!-- Monday -->
                    <div class="week-view-day border-l">
                        <div class="text-center font-semibold p-2 border-b">
                            Mon<br>May 12
                        </div>
                        <div class="relative">
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            
                            <!-- Task Example -->
                            <div class="absolute top-24 left-0 right-0 mx-1 p-1 bg-error text-error-content text-xs rounded" style="height: 36px;" onclick="openTaskDetails(4)">
                                Product Demo Video
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tuesday -->
                    <div class="week-view-day border-l">
                        <div class="text-center font-semibold p-2 border-b">
                            Tue<br>May 13
                        </div>
                        <div class="relative">
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                        </div>
                    </div>
                    
                    <!-- Wednesday -->
                    <div class="week-view-day border-l">
                        <div class="text-center font-semibold p-2 border-b">
                            Wed<br>May 14
                        </div>
                        <div class="relative">
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                        </div>
                    </div>
                    
                    <!-- Thursday -->
                    <div class="week-view-day border-l">
                        <div class="text-center font-semibold p-2 border-b">
                            Thu<br>May 15
                        </div>
                        <div class="relative">
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            
                            <!-- Task Example -->
                            <div class="absolute top-48 left-0 right-0 mx-1 p-1 bg-error text-error-content text-xs rounded" style="height: 36px;" onclick="openTaskDetails(1)">
                                Homepage Redesign
                            </div>
                        </div>
                    </div>
                    
                    <!-- Friday -->
                    <div class="week-view-day border-l">
                        <div class="text-center font-semibold p-2 border-b bg-base-200">
                            Fri<br>May 16
                        </div>
                        <div class="relative">
                            <div class="h-12 border-b bg-base-200"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            
                            <!-- Current Time Indicator -->
                            <div class="absolute top-36 left-0 right-0 border-t-2 border-primary z-20"></div>
                        </div>
                    </div>
                    
                    <!-- Saturday -->
                    <div class="week-view-day border-l border-r">
                        <div class="text-center font-semibold p-2 border-b">
                            Sat<br>May 17
                        </div>
                        <div class="relative">
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                            <div class="h-12 border-b"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Day View (Hidden by default) -->
<div id="day-view" class="card bg-base-100 shadow-md hidden">
    <div class="card-body p-4">
        <div class="text-center font-semibold text-lg mb-4">
            Friday, May 16, 2025
        </div>
        
        <div class="flex">
            <!-- Time Column -->
            <div class="w-16 flex-shrink-0">
                <div class="text-xs text-right pr-2 h-12">8:00 AM</div>
                <div class="text-xs text-right pr-2 h-12">9:00 AM</div>
                <div class="text-xs text-right pr-2 h-12">10:00 AM</div>
                <div class="text-xs text-right pr-2 h-12">11:00 AM</div>
                <div class="text-xs text-right pr-2 h-12">12:00 PM</div>
                <div class="text-xs text-right pr-2 h-12">1:00 PM</div>
                <div class="text-xs text-right pr-2 h-12">2:00 PM</div>
                <div class="text-xs text-right pr-2 h-12">3:00 PM</div>
                <div class="text-xs text-right pr-2 h-12">4:00 PM</div>
                <div class="text-xs text-right pr-2 h-12">5:00 PM</div>
                <div class="text-xs text-right pr-2 h-12">6:00 PM</div>
                <div class="text-xs text-right pr-2 h-12">7:00 PM</div>
            </div>
            
            <!-- Day Schedule -->
            <div class="flex-1 relative border-l">
                <div class="day-view-hour"></div>
                <div class="day-view-hour"></div>
                <div class="day-view-hour bg-base-200"></div> <!-- Current hour -->
                <div class="day-view-hour"></div>
                <div class="day-view-hour"></div>
                <div class="day-view-hour"></div>
                <div class="day-view-hour"></div>
                <div class="day-view-hour"></div>
                <div class="day-view-hour"></div>
                <div class="day-view-hour"></div>
                <div class="day-view-hour"></div>
                <div class="day-view-hour"></div>
                
                <!-- Current Time Indicator -->
                <div class="absolute top-[125px] left-0 right-0 border-t-2 border-primary z-20"></div>
                
                <!-- Task Examples -->
                <div class="day-view-task bg-warning text-warning-content" style="top: 180px; height: 45px;" onclick="openTaskDetails(6)">
                    Team Standup Meeting
                </div>
                
                <div class="day-view-task bg-info text-info-content" style="top: 300px; height: 90px;" onclick="openTaskDetails(7)">
                    Client Presentation Prep
                </div>
                
                <div class="day-view-task bg-success text-success-content" style="top: 450px; height: 60px;" onclick="openTaskDetails(8)">
                    Design Review
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
