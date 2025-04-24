@extends('layout.app')

@section('title', 'Calendar')

@section('content')
<div class="card shadow-lg bg-base-100">
    <div class="card-body">
        <!-- Calendar Controls and Filters - Jira Style -->
        <div class="mb-6">
            <!-- Main controls and view options -->
            <div class="flex flex-col md:flex-row justify-between mb-4 gap-4">
                <div class="flex items-center space-x-2">
                    <button id="prev-month" class="btn btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <h2 id="current-month" class="text-xl font-semibold"></h2>
                    <button id="next-month" class="btn btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <button id="today-btn" class="btn btn-sm btn-primary ml-2">Today</button>
                </div>

                <div class="flex items-center space-x-2">
                    <div class="btn-group">
                        <button id="view-month" class="btn btn-sm btn-active">Month</button>
                        <button id="view-week" class="btn btn-sm">Week</button>
                        <button id="view-day" class="btn btn-sm">Day</button>
                    </div>
                </div>
            </div>

            <!-- Filters - Jira Style -->
            <div class="flex flex-col md:flex-row gap-3 justify-between bg-base-200 p-3 rounded-lg">
                <div class="flex flex-wrap gap-2 items-center">
                    <span class="font-medium text-sm">Filters:</span>
                    
                    <select id="project-filter" class="select select-sm select-bordered">
                        <option value="">All Projects</option>
                        <!-- Project options will be populated by JS -->
                    </select>
                    
                    <select id="priority-filter" class="select select-sm select-bordered">
                        <option value="">All Priorities</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                    
                    <select id="status-filter" class="select select-sm select-bordered">
                        <option value="">All Statuses</option>
                        <option value="todo">To Do</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                
                <div class="flex items-center gap-2">
                    <div class="form-control">
                        <label class="cursor-pointer label p-0">
                            <input type="checkbox" id="my-tasks-only" class="checkbox checkbox-sm checkbox-primary" checked />
                            <span class="label-text ml-2">My tasks only</span>
                        </label>
                    </div>
                    
                    <button id="clear-filters" class="btn btn-sm btn-ghost">
                        Clear filters
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Calendar Grid -->
        <div class="overflow-x-auto">
            <div class="calendar-container">
                <div id="calendar-header" class="grid grid-cols-7 gap-px mb-px bg-base-300">
                    <div class="text-center font-medium p-2 bg-base-100">Sun</div>
                    <div class="text-center font-medium p-2 bg-base-100">Mon</div>
                    <div class="text-center font-medium p-2 bg-base-100">Tue</div>
                    <div class="text-center font-medium p-2 bg-base-100">Wed</div>
                    <div class="text-center font-medium p-2 bg-base-100">Thu</div>
                    <div class="text-center font-medium p-2 bg-base-100">Fri</div>
                    <div class="text-center font-medium p-2 bg-base-100">Sat</div>
                </div>
                <div id="calendar-days" class="grid grid-cols-7 gap-px bg-base-300">
                    <!-- Calendar days will be inserted here via JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Task Modal -->
<dialog id="task-modal" class="modal">
    <div class="modal-box max-w-4xl bg-base-100 shadow-xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-2xl text-primary" id="modal-date"></h3>
            <form method="dialog">
                <button class="btn btn-circle btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </form>
        </div>
        
        <div class="divider"></div>
        
        <div id="tasks-container">
            <!-- Tasks Tab -->
            <div id="modal-tasks" class="tab-content">
                <div class="flex justify-between items-center mb-5">
                    <div class="flex items-center bg-base-200 rounded-lg overflow-hidden pr-2">
                        <input type="text" placeholder="Search tasks..." class="input input-bordered border-0 bg-base-200 w-full max-w-xs" id="task-search">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-base-content opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <div class="dropdown dropdown-end">
                        <label tabindex="0" class="btn btn-sm btn-outline">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span>Sort</span>
                        </label>
                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                            <li><a data-sort="priority">Priority</a></li>
                            <li><a data-sort="duedate">Due Date</a></li>
                            <li><a data-sort="status">Status</a></li>
                        </ul>
                    </div>
                </div>
                
                <div id="tasks-list" class="space-y-4">
                    <!-- Tasks will be loaded here -->
                </div>
            </div>
                    </div>
                    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
                </form>
</dialog>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tasks = @json($tasks);
    const projects = @json($projects);
    const assignedTaskIds = @json($assignedTaskIds);
    
    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();
    let currentDay = currentDate.getDate();
    let currentView = 'month'; // month, week, day
    
    // Filter states
    let filters = {
        project: '',
        priority: '',
        status: '',
        myTasksOnly: true,
        search: ''
    };
    
    // DOM Elements
    const prevMonthBtn = document.getElementById('prev-month');
    const nextMonthBtn = document.getElementById('next-month');
    const todayBtn = document.getElementById('today-btn');
    const currentMonthElement = document.getElementById('current-month');
    const calendarDaysElement = document.getElementById('calendar-days');
    const calendarHeaderElement = document.getElementById('calendar-header');
    const taskModal = document.getElementById('task-modal');
    const modalDate = document.getElementById('modal-date');
    const modalTasks = document.getElementById('modal-tasks');
    const tasksListElement = document.getElementById('tasks-list');
    
    // View Buttons
    const viewMonthBtn = document.getElementById('view-month');
    const viewWeekBtn = document.getElementById('view-week');
    const viewDayBtn = document.getElementById('view-day');
    
    // Filter Elements
    const projectFilter = document.getElementById('project-filter');
    const priorityFilter = document.getElementById('priority-filter');
    const statusFilter = document.getElementById('status-filter');
    const myTasksOnlyCheckbox = document.getElementById('my-tasks-only');
    const clearFiltersBtn = document.getElementById('clear-filters');
    const taskSearch = document.getElementById('task-search');
    
    // Modal Tabs
    const tabAllTasks = document.getElementById('tab-all-tasks');
    
    // Populate project options in filters and form
    populateProjectOptions();
    
    // Initialize calendar
    renderCalendar();
    
    // Event listeners for navigation
    prevMonthBtn.addEventListener('click', () => {
        if (currentView === 'month') {
            // Move back one month in month view
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
            }
        } else if (currentView === 'week') {
            // Move back one week in week view
            const currentDate = new Date(currentYear, currentMonth, currentDay);
            currentDate.setDate(currentDate.getDate() - 7);
            currentDay = currentDate.getDate();
            currentMonth = currentDate.getMonth();
            currentYear = currentDate.getFullYear();
        } else if (currentView === 'day') {
            // Move back one day in day view
            const currentDate = new Date(currentYear, currentMonth, currentDay);
            currentDate.setDate(currentDate.getDate() - 1);
            currentDay = currentDate.getDate();
            currentMonth = currentDate.getMonth();
            currentYear = currentDate.getFullYear();
        }
        renderCalendar();
    });
    
    nextMonthBtn.addEventListener('click', () => {
        if (currentView === 'month') {
            // Move forward one month in month view
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
            }
        } else if (currentView === 'week') {
            // Move forward one week in week view
            const currentDate = new Date(currentYear, currentMonth, currentDay);
            currentDate.setDate(currentDate.getDate() + 7);
            currentDay = currentDate.getDate();
            currentMonth = currentDate.getMonth();
            currentYear = currentDate.getFullYear();
        } else if (currentView === 'day') {
            // Move forward one day in day view
            const currentDate = new Date(currentYear, currentMonth, currentDay);
            currentDate.setDate(currentDate.getDate() + 1);
            currentDay = currentDate.getDate();
            currentMonth = currentDate.getMonth();
            currentYear = currentDate.getFullYear();
        }
        renderCalendar();
    });
    
    todayBtn.addEventListener('click', () => {
        // Set date to today
        const today = new Date();
        currentDay = today.getDate();
        currentMonth = today.getMonth();
        currentYear = today.getFullYear();
        
        // Always show today's view based on current view mode
        if (currentView === 'month') {
            // In month view, just show the current month containing today
        renderCalendar();
        } else if (currentView === 'week' || currentView === 'day') {
            // For week/day view, explicitly go to today
            renderCalendar();
            
            // Scroll to today if needed (for better user experience)
            setTimeout(() => {
                const todayElement = document.querySelector('.is-today');
                if (todayElement) {
                    todayElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }, 100);
        }
    });
    
    // View switch event listeners
    viewMonthBtn.addEventListener('click', () => {
        setActiveView('month');
    });
    
    viewWeekBtn.addEventListener('click', () => {
        setActiveView('week');
    });
    
    viewDayBtn.addEventListener('click', () => {
        setActiveView('day');
    });
    
    // Filter event listeners
    projectFilter.addEventListener('change', () => {
        filters.project = projectFilter.value;
        renderCalendar();
    });
    
    priorityFilter.addEventListener('change', () => {
        filters.priority = priorityFilter.value;
        renderCalendar();
    });
    
    statusFilter.addEventListener('change', () => {
        filters.status = statusFilter.value;
        renderCalendar();
    });
    
    myTasksOnlyCheckbox.addEventListener('change', () => {
        filters.myTasksOnly = myTasksOnlyCheckbox.checked;
        renderCalendar();
    });
    
    clearFiltersBtn.addEventListener('click', () => {
        projectFilter.value = '';
        priorityFilter.value = '';
        statusFilter.value = '';
        myTasksOnlyCheckbox.checked = true;
        taskSearch.value = '';
        
        filters = {
            project: '',
            priority: '',
            status: '',
            myTasksOnly: true,
            search: ''
        };
        
        renderCalendar();
    });
    
    taskSearch.addEventListener('input', () => {
        filters.search = taskSearch.value.toLowerCase();
        renderTasksList(); // Only update the task list, not the whole calendar
    });
    
    // Tab switching in modal
    tabAllTasks.addEventListener('click', () => {
        tabAllTasks.classList.add('tab-active');
    });
    
    // Helper Functions
    function populateProjectOptions() {
        // Add project options to filter dropdown
        projects.forEach(project => {
            const option = document.createElement('option');
            option.value = project.id;
            option.textContent = project.name;
            projectFilter.appendChild(option);
        });
    }
    
    function formatDateForInput(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    
    // Render calendar function
    function renderCalendar() {
        // Update month/year display
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        currentMonthElement.textContent = `${monthNames[currentMonth]} ${currentYear}`;
        
        // Clear previous calendar
        calendarDaysElement.innerHTML = '';
        
        // Filter tasks
        const filteredTasks = filterTasks(tasks);
        
        if (currentView === 'month') {
            // Show calendar header for month view
            calendarHeaderElement.style.display = 'grid';
            renderMonthView(filteredTasks);
        } else if (currentView === 'week') {
            // Show calendar header for week view
            calendarHeaderElement.style.display = 'grid';
            renderWeekView(filteredTasks);
        } else if (currentView === 'day') {
            // Hide calendar header for day view
            calendarHeaderElement.style.display = 'none';
            renderDayView(filteredTasks);
        }
    }
    
    function renderMonthView(filteredTasks) {
        // Get first day of month and number of days
        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        
        // Calculate previous month's days needed
        const prevMonthDays = new Date(currentYear, currentMonth, 0).getDate();
        
        // Today's date for comparison
        const today = new Date();
        const currentToday = today.getDate();
        const currentTodayMonth = today.getMonth();
        const currentTodayYear = today.getFullYear();
        
        // Add empty cells for previous month
        for (let i = 0; i < firstDay; i++) {
            const prevDate = prevMonthDays - firstDay + i + 1;
            const dayElement = createDayElement(prevDate, 'prev-month');
            calendarDaysElement.appendChild(dayElement);
        }
        
        // Add current month days
        for (let day = 1; day <= daysInMonth; day++) {
            const isToday = day === currentToday && 
                          currentMonth === currentTodayMonth && 
                          currentYear === currentTodayYear;
            
            const dayElement = createDayElement(day, isToday ? 'current-month is-today' : 'current-month');
            
            // Add tasks for this day
            const dayTasks = getTasksForDay(day, filteredTasks);
            
            // Add tasks to this day's content
            if (dayTasks.length > 0) {
                const dayContent = dayElement.querySelector('.day-content');
                
                // Show up to 5 tasks
                const tasksToShow = dayTasks.slice(0, 5);
                tasksToShow.forEach(task => {
                    const taskItem = document.createElement('div');
                    const taskStatus = task.status || task.current_status || 'unknown';
                    const priorityClass = `priority-${task.priority ? task.priority.toLowerCase() : 'medium'}`;
                    
                    taskItem.className = `task-item ${priorityClass}`;
                    
                    // Task title with repetitive indicator if applicable
                    const taskTitle = document.createElement('span');
                    taskTitle.textContent = task.title;
                    
                    taskItem.appendChild(taskTitle);
                    
                    // Add repetitive task indicator if this is a repetitive task
                    if (task.is_repetitive) {
                        const repetitiveBadge = document.createElement('span');
                        repetitiveBadge.className = 'badge badge-secondary badge-sm';
                        repetitiveBadge.textContent = `Repeats ${task.repetition_rate}`;
                        taskTitle.appendChild(repetitiveBadge);
                    }
                    
                    // Project tag if available
                    if (task.project && task.project.name) {
                        const projectTag = document.createElement('div');
                        projectTag.className = 'project-tag mt-1';
                        projectTag.textContent = task.project.name;
                        taskItem.appendChild(projectTag);
                    }
                    
                    dayContent.appendChild(taskItem);
                });
                
                // Add "more" indicator if needed
                if (dayTasks.length > 5) {
                    const moreTasks = document.createElement('div');
                    moreTasks.className = 'text-xs text-right mt-1';
                    moreTasks.textContent = `+${dayTasks.length - 5} more`;
                    dayContent.appendChild(moreTasks);
                }
                
                dayElement.classList.add('cursor-pointer');
                dayElement.addEventListener('click', () => {
                    showTasksModal(day, dayTasks);
                });
                
                // Add double-click to go to day view
                dayElement.addEventListener('dblclick', () => {
                    currentDay = day;
                    setActiveView('day');
                });
            }
            
            calendarDaysElement.appendChild(dayElement);
        }
        
        // Fill remaining cells with next month
        const totalDays = 42; // 6 rows × 7 days
        const remainingDays = totalDays - (firstDay + daysInMonth);
        for (let i = 1; i <= remainingDays; i++) {
            const dayElement = createDayElement(i, 'next-month');
            calendarDaysElement.appendChild(dayElement);
        }
    }
    
    function renderWeekView(filteredTasks) {
        // Reset calendar grid (in case of previous day view)
        calendarDaysElement.className = 'grid grid-cols-7 gap-px bg-base-300';
        
        // Get the current date and find the current week
        const currentDate = new Date(currentYear, currentMonth, currentDay);
        
        // Get the start of the week (Sunday)
        const weekStart = new Date(currentDate);
        const dayOfWeek = currentDate.getDay();
        weekStart.setDate(currentDate.getDate() - dayOfWeek);
        
        // Today's date for comparison
        const today = new Date();
        
        // Add days for the week
        for (let i = 0; i < 7; i++) {
            const dayDate = new Date(weekStart);
            dayDate.setDate(weekStart.getDate() + i);
            
            const dayOfMonth = dayDate.getDate();
            const monthOfDay = dayDate.getMonth();
            const yearOfDay = dayDate.getFullYear();
            
            const isCurrentMonth = monthOfDay === currentMonth;
            const isToday = 
                dayOfMonth === today.getDate() && 
                monthOfDay === today.getMonth() && 
                yearOfDay === today.getFullYear();
            
            // Create day container
        const dayElement = document.createElement('div');
            dayElement.className = `day week-day ${isCurrentMonth ? 'current-month' : 'other-month'} ${isToday ? 'is-today' : ''} bg-base-100`;
        
            // Day header
        const dayHeader = document.createElement('div');
            dayHeader.className = 'week-header';
            
            const dayOfWeekLabel = document.createElement('div');
            dayOfWeekLabel.textContent = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'][dayDate.getDay()];
            
            const dateLabel = document.createElement('div');
            dateLabel.className = 'week-date';
            dateLabel.textContent = dayOfMonth;
            
            dayHeader.appendChild(dayOfWeekLabel);
            dayHeader.appendChild(dateLabel);
            
            // Day content
        const dayContent = document.createElement('div');
        dayContent.className = 'day-content';
        
            // Get tasks for this day
        const dayTasks = filteredTasks.filter(task => {
            const dueDate = task.due_date ? new Date(task.due_date) : null;
            if (!dueDate) return false;
            
                return dueDate.getDate() === dayOfMonth && 
                       dueDate.getMonth() === monthOfDay && 
                       dueDate.getFullYear() === yearOfDay;
        });
        
        // Add tasks to day content
            dayTasks.forEach(task => {
                    const taskItem = document.createElement('div');
                    const taskStatus = task.status || task.current_status || 'unknown';
                    const priorityClass = `priority-${task.priority ? task.priority.toLowerCase() : 'medium'}`;
                    
                    taskItem.className = `task-item ${priorityClass}`;
                    
                    // Task title with repetitive indicator if applicable
                    const taskTitle = document.createElement('span');
                    taskTitle.textContent = task.title;
                    
                    taskItem.appendChild(taskTitle);
                    
                    // Add repetitive task indicator if this is a repetitive task
                    if (task.is_repetitive) {
                        const repetitiveBadge = document.createElement('span');
                        repetitiveBadge.className = 'badge badge-secondary badge-sm';
                        repetitiveBadge.textContent = `Repeats ${task.repetition_rate}`;
                        taskTitle.appendChild(repetitiveBadge);
                    }
                    
                    // Project tag if available
                    if (task.project && task.project.name) {
                        const projectTag = document.createElement('div');
                        projectTag.className = 'project-tag mt-1';
                        projectTag.textContent = task.project.name;
                        taskItem.appendChild(projectTag);
                    }
                    
                    dayContent.appendChild(taskItem);
                });
                
            if (dayTasks.length > 0) {
                dayElement.classList.add('cursor-pointer');
                dayElement.addEventListener('click', () => {
                    const modalDate = new Date(yearOfDay, monthOfDay, dayOfMonth);
                    showTasksModal(dayOfMonth, dayTasks, modalDate);
                });
            }
            
            dayElement.appendChild(dayHeader);
            dayElement.appendChild(dayContent);
            calendarDaysElement.appendChild(dayElement);
        }
    }
    
    function renderDayView(filteredTasks) {
        // Change grid layout for day view
        calendarDaysElement.className = 'grid grid-cols-1 gap-px bg-base-300';
        
        // Current date to display
        const currentDate = new Date(currentYear, currentMonth, currentDay);
        
        // Check if this is today's date
        const today = new Date();
        const isToday = 
            currentDate.getDate() === today.getDate() && 
            currentDate.getMonth() === today.getMonth() && 
            currentDate.getFullYear() === today.getFullYear();
        
        // Create day container
        const dayElement = document.createElement('div');
        dayElement.className = `day-view-container bg-base-100 ${isToday ? 'is-today' : ''}`;
        
        // Day header
        const dayHeader = document.createElement('div');
        dayHeader.className = `day-view-header ${isToday ? 'bg-primary/10' : ''}`;
        
        const dayTitle = document.createElement('div');
        dayTitle.className = 'day-view-title';
        dayTitle.textContent = currentDate.toLocaleDateString(undefined, {
            weekday: 'long',
            month: 'long',
            day: 'numeric',
            year: 'numeric'
        });
        dayHeader.appendChild(dayTitle);
        
        // Day content
        const dayContent = document.createElement('div');
        dayContent.className = 'day-view-content';
        
        // Get tasks for this day
        const dayTasks = filteredTasks.filter(task => {
            const dueDate = task.due_date ? new Date(task.due_date) : null;
            if (!dueDate) return false;
            
            return dueDate.getDate() === currentDate.getDate() && 
                   dueDate.getMonth() === currentDate.getMonth() && 
                   dueDate.getFullYear() === currentDate.getFullYear();
        });
        
        if (dayTasks.length === 0) {
            // No tasks
            const emptyState = document.createElement('div');
            emptyState.className = 'flex flex-col items-center justify-center py-10 text-base-content/70';
            emptyState.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="text-lg font-medium">No tasks for this day</p>
            `;
            dayContent.appendChild(emptyState);
        } else {
            // Display tasks for the day
            const taskContainer = document.createElement('div');
            taskContainer.className = 'space-y-4';
            
            dayTasks.forEach(task => {
                const taskCard = document.createElement('div');
                taskCard.className = 'card bg-base-100 shadow-sm border border-base-200';
                
                // Priority color
                const priorityColor = getPriorityColor(task.priority);
                if (priorityColor) {
                    const colorStrip = document.createElement('div');
                    colorStrip.className = `absolute left-0 top-0 h-full w-1 ${priorityColor}`;
                    taskCard.appendChild(colorStrip);
                }
                
                const cardBody = document.createElement('div');
                cardBody.className = 'card-body p-4 pl-6';
                
                // Task header
                const header = document.createElement('div');
                header.className = 'flex justify-between items-start mb-3';
                
                // Title container that will hold title and assigned badge
                const titleContainer = document.createElement('div');
                titleContainer.className = 'flex flex-col';
                
                // Title
                const title = document.createElement('h3');
                title.className = 'card-title text-lg';
                title.textContent = task.title;
                titleContainer.appendChild(title);
                
                // Assigned to me badge
                if (task.assigned_to_user) {
                    const assignedBadge = document.createElement('div');
                    assignedBadge.className = 'badge badge-sm badge-primary mt-1';
                    assignedBadge.textContent = 'Assigned to me';
                    titleContainer.appendChild(assignedBadge);
                }
                
                header.appendChild(titleContainer);
                
                // Badges container
                const badgesContainer = document.createElement('div');
                badgesContainer.className = 'flex flex-wrap gap-2';
                
                // Status badge
                const taskStatus = task.status || task.current_status || 'unknown';
                const statusBadge = document.createElement('span');
                statusBadge.className = `badge ${getStatusBadgeClass(taskStatus)} badge-sm`;
                statusBadge.textContent = formatStatus(taskStatus);
                badgesContainer.appendChild(statusBadge);
                
                // Priority badge
                const priorityBadge = document.createElement('span');
                let priorityClass = '';
                switch((task.priority || '').toLowerCase()) {
                    case 'high':
                        priorityClass = 'badge-error';
                        break;
                    case 'medium':
                        priorityClass = 'badge-warning';
                        break;
                    case 'low':
                        priorityClass = 'badge-info';
                        break;
                    default:
                        priorityClass = 'badge-ghost';
                }
                priorityBadge.className = `badge ${priorityClass} badge-sm`;
                priorityBadge.textContent = task.priority || 'Normal';
                badgesContainer.appendChild(priorityBadge);
                
                // Repetitive task badge
                if (task.is_repetitive) {
                    const repetitiveBadge = document.createElement('span');
                    repetitiveBadge.className = 'badge badge-secondary badge-sm';
                    repetitiveBadge.textContent = `Repeats ${task.repetition_rate}`;
                    badgesContainer.appendChild(repetitiveBadge);
                }
                
                header.appendChild(badgesContainer);
                cardBody.appendChild(header);
                
                // Description
                if (task.description) {
                    const description = document.createElement('p');
                    description.className = 'text-sm mb-3';
                    description.textContent = task.description;
                    cardBody.appendChild(description);
                }
                
                // Meta info
                const metaInfo = document.createElement('div');
                metaInfo.className = 'flex justify-between text-sm text-base-content/70';
                
                // Project info
                const projectInfo = document.createElement('div');
                if (task.project && task.project.name) {
                    projectInfo.textContent = `Project: ${task.project.name}`;
                } else {
                    projectInfo.textContent = 'No project';
                }
                
                // Priority info
                const priorityInfo = document.createElement('div');
                priorityInfo.textContent = `Priority: ${task.priority || 'Normal'}`;
                
                metaInfo.appendChild(projectInfo);
                metaInfo.appendChild(priorityInfo);
                cardBody.appendChild(metaInfo);
                
                // View button
                const actions = document.createElement('div');
                actions.className = 'card-actions justify-end mt-3';
                
                const viewBtn = document.createElement('a');
                viewBtn.href = `/tasks/${task.id}`;
                viewBtn.className = 'btn btn-sm btn-outline btn-primary';
                viewBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    View Details
                `;
                actions.appendChild(viewBtn);
                
                cardBody.appendChild(actions);
                taskCard.appendChild(cardBody);
                taskContainer.appendChild(taskCard);
            });
            
            dayContent.appendChild(taskContainer);
        }
        
        dayElement.appendChild(dayHeader);
        dayElement.appendChild(dayContent);
        calendarDaysElement.appendChild(dayElement);
    }
    
    function createDayElement(day, className) {
        const dayElement = document.createElement('div');
        dayElement.className = `day ${className} bg-base-100`;
        
        // Create day header
        const dayHeader = document.createElement('div');
        dayHeader.className = 'day-header';
        
        // Day number
        const dayNumber = document.createElement('div');
        dayNumber.className = 'day-number';
        dayNumber.textContent = day;
        dayHeader.appendChild(dayNumber);
        
        // Day content area
        const dayContent = document.createElement('div');
        dayContent.className = 'day-content';
        
        dayElement.appendChild(dayHeader);
        dayElement.appendChild(dayContent);
        
        return dayElement;
    }
    
    function getTasksForDay(day, filteredTasks) {
        // Create date in local timezone
        const date = new Date(currentYear, currentMonth, day);
        
        // Get tasks for this day from filtered tasks
        return filteredTasks.filter(task => {
            if (!task.due_date) return false;
            
            // Create task date in local timezone
            const dueDate = new Date(task.due_date);
            
            // Compare dates in local timezone
            return dueDate.getFullYear() === date.getFullYear() &&
                   dueDate.getMonth() === date.getMonth() &&
                   dueDate.getDate() === date.getDate();
        });
    }
    
    function filterTasks(allTasks) {
        return allTasks.filter(task => {
            // Filter by project
            if (filters.project && task.project_id != filters.project) {
                return false;
            }
            
            // Filter by priority
            if (filters.priority && 
                task.priority && 
                task.priority.toLowerCase() !== filters.priority.toLowerCase()) {
                return false;
            }
            
            // Filter by status (check both status and current_status)
            if (filters.status) {
                const taskStatus = (task.status || task.current_status || '').toLowerCase();
                if (!taskStatus.includes(filters.status.toLowerCase())) {
                    return false;
                }
            }
            
            // Filter by my tasks only
            if (filters.myTasksOnly && !task.assigned_to_user) {
                return false;
            }
            
            // Filter by search
            if (filters.search) {
                const searchableText = (task.title + ' ' + (task.description || '')).toLowerCase();
                if (!searchableText.includes(filters.search)) {
                    return false;
                }
            }
            
            return true;
        });
    }
    
    function showTasksModal(day, dayTasks, dateOverride = null) {
        const date = dateOverride || new Date(currentYear, currentMonth, day);
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        
        // Create a more elegant date display
        modalDate.innerHTML = `
            <div class="flex flex-col">
                <div class="flex items-center">
                    <span class="text-3xl font-bold mr-2">${date.getDate()}</span>
                    <div class="flex flex-col">
                        <span class="font-medium">${date.toLocaleDateString(undefined, { month: 'long' })}</span>
                        <span class="text-sm opacity-70">${date.toLocaleDateString(undefined, { weekday: 'long' })}, ${date.getFullYear()}</span>
                    </div>
                </div>
                <div class="flex mt-2 text-sm space-x-2">
                    <button class="link link-hover text-primary" id="view-this-day">Day View</button>
                    <button class="link link-hover text-primary" id="view-this-week">Week View</button>
                </div>
            </div>
        `;
        
        // Add event listeners to view links
        setTimeout(() => {
            const viewDayLink = document.getElementById('view-this-day');
            const viewWeekLink = document.getElementById('view-this-week');
            
            if (viewDayLink) {
                viewDayLink.addEventListener('click', () => {
                    currentDay = date.getDate();
                    currentMonth = date.getMonth();
                    currentYear = date.getFullYear();
                    taskModal.close();
                    setActiveView('day');
                });
            }
            
            if (viewWeekLink) {
                viewWeekLink.addEventListener('click', () => {
                    currentDay = date.getDate();
                    currentMonth = date.getMonth();
                    currentYear = date.getFullYear();
                    taskModal.close();
                    setActiveView('week');
                });
            }
        }, 10);
        
        renderTasksList(dayTasks);
        
        taskModal.showModal();
    }
    
    function renderTasksList(specificTasks = null) {
        // Clear task list
        tasksListElement.innerHTML = '';
        
        // Use either the specific tasks or filter all tasks
        const tasksToShow = specificTasks || filterTasks(tasks);
        
        if (tasksToShow.length === 0) {
            tasksListElement.innerHTML = `
                <div class="flex flex-col items-center justify-center py-8 text-base-content/70">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-center text-lg font-medium">No tasks for this day</p>
                </div>
            `;
            return;
        }
        
        tasksToShow.forEach(task => {
            // Create task card
            const taskCard = document.createElement('div');
            taskCard.className = 'card bg-base-100 shadow-sm hover:shadow-md transition-shadow duration-300 border border-base-200 overflow-hidden';
            
            // Priority color strip
            const priorityColor = getPriorityColor(task.priority);
            if (priorityColor) {
                const colorStrip = document.createElement('div');
                colorStrip.className = `absolute top-0 left-0 w-full h-1 ${priorityColor}`;
                taskCard.appendChild(colorStrip);
                taskCard.style.paddingTop = '4px';
            }
            
            // Create card body
            const cardBody = document.createElement('div');
            cardBody.className = 'card-body p-5';
            
            // Header with status and priority badges
            const header = document.createElement('div');
            header.className = 'flex flex-wrap justify-between items-start gap-2 mb-3';
            
            // Task title
            const title = document.createElement('h3');
            title.className = 'card-title text-lg flex-1';
            title.textContent = task.title;
            
            // Add assigned to me badge if applicable
            if (task.assigned_to_user) {
                const assignedBadge = document.createElement('span');
                assignedBadge.className = 'badge badge-sm badge-primary mr-1';
                assignedBadge.textContent = 'Assigned to me';
                title.appendChild(assignedBadge);
            }
            
            header.appendChild(title);
            
            // Badges container
            const badgesContainer = document.createElement('div');
            badgesContainer.className = 'flex flex-wrap gap-2';
            
            // Status badge
            const taskStatus = task.status || task.current_status || 'unknown';
            const statusBadge = document.createElement('span');
            statusBadge.className = `badge ${getStatusBadgeClass(taskStatus)} badge-sm`;
            statusBadge.textContent = formatStatus(taskStatus);
            badgesContainer.appendChild(statusBadge);
            
            // Priority badge
            const priorityBadge = document.createElement('span');
            let priorityClass = '';
            switch((task.priority || '').toLowerCase()) {
                case 'high':
                    priorityClass = 'badge-error';
                    break;
                case 'medium':
                    priorityClass = 'badge-warning';
                    break;
                case 'low':
                    priorityClass = 'badge-info';
                    break;
                default:
                    priorityClass = 'badge-ghost';
            }
            priorityBadge.className = `badge ${priorityClass} badge-sm`;
            priorityBadge.textContent = task.priority || 'Normal';
            badgesContainer.appendChild(priorityBadge);
            
            // Repetitive task badge
            if (task.is_repetitive) {
                const repetitiveBadge = document.createElement('span');
                repetitiveBadge.className = 'badge badge-secondary badge-sm';
                repetitiveBadge.textContent = `Repeats ${task.repetition_rate}`;
                badgesContainer.appendChild(repetitiveBadge);
            }
            
            header.appendChild(badgesContainer);
            cardBody.appendChild(header);
            
            // Task description
            if (task.description) {
                const description = document.createElement('p');
                description.className = 'text-sm text-base-content/80 mb-4 line-clamp-2';
                description.textContent = task.description;
                cardBody.appendChild(description);
            }
            
            // Footer with project and date info
            const footer = document.createElement('div');
            footer.className = 'flex justify-between items-center mt-auto pt-3 border-t border-base-200 text-sm';
            
            // Project info with icon
            const projectInfo = document.createElement('div');
            projectInfo.className = 'flex items-center gap-1 text-base-content/70';
            
            // Project icon
            const projectIcon = document.createElement('span');
            projectIcon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
            </svg>`;
            projectInfo.appendChild(projectIcon);
            
            // Project name
            const projectName = document.createElement('span');
            if (task.project && task.project.name) {
                projectName.textContent = task.project.name;
            } else {
                projectName.textContent = 'No project';
            }
            projectInfo.appendChild(projectName);
            
            // Due date with icon
            const dueDateContainer = document.createElement('div');
            dueDateContainer.className = 'flex items-center gap-1 text-base-content/70';
            
            // Calendar icon
            const calendarIcon = document.createElement('span');
            calendarIcon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>`;
            dueDateContainer.appendChild(calendarIcon);
            
            // Due date text
            const dueDateText = document.createElement('span');
            if (task.due_date) {
                const date = new Date(task.due_date);
                dueDateText.textContent = date.toLocaleDateString(undefined, {
                    month: 'short', 
                    day: 'numeric',
                    year: date.getFullYear() !== new Date().getFullYear() ? 'numeric' : undefined
                });
            } else {
                dueDateText.textContent = 'No due date';
            }
            dueDateContainer.appendChild(dueDateText);
            
            footer.appendChild(projectInfo);
            footer.appendChild(dueDateContainer);
            cardBody.appendChild(footer);
            
            // Action buttons
            const actions = document.createElement('div');
            actions.className = 'card-actions justify-end mt-4';
            
            const viewBtn = document.createElement('a');
            viewBtn.href = `/tasks/${task.id}`;
            viewBtn.className = 'btn btn-sm btn-outline btn-primary';
            viewBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                View Details
            `;
            actions.appendChild(viewBtn);
            
            cardBody.appendChild(actions);
            taskCard.appendChild(cardBody);
            
            tasksListElement.appendChild(taskCard);
        });
    }
    
    function formatStatus(status) {
        if (!status) return 'Unknown';
        
        // Convert snake_case or kebab-case to Title Case
        return status
            .toLowerCase()
            .replace(/_/g, ' ')
            .replace(/-/g, ' ')
            .replace(/\b\w/g, char => char.toUpperCase());
    }
    
    function getPriorityColor(priority) {
        if (!priority) return '';
        
        switch(priority.toLowerCase()) {
            case 'high':
                return 'bg-error';
            case 'medium':
                return 'bg-warning';
            case 'low':
                return 'bg-info';
            default:
                return '';
        }
    }
    
    function setActiveView(view) {
        currentView = view;
        
        // Update button states
        viewMonthBtn.classList.remove('btn-active');
        viewWeekBtn.classList.remove('btn-active');
        viewDayBtn.classList.remove('btn-active');
        
        // Update navigation button tooltips based on view
        if (view === 'month') {
            prevMonthBtn.setAttribute('title', 'Previous Month');
            nextMonthBtn.setAttribute('title', 'Next Month');
                viewMonthBtn.classList.add('btn-active');
        } else if (view === 'week') {
            prevMonthBtn.setAttribute('title', 'Previous Week');
            nextMonthBtn.setAttribute('title', 'Next Week');
                viewWeekBtn.classList.add('btn-active');
        } else if (view === 'day') {
            prevMonthBtn.setAttribute('title', 'Previous Day');
            nextMonthBtn.setAttribute('title', 'Next Day');
                viewDayBtn.classList.add('btn-active');
        }
        
        renderCalendar();
    }
    
    function getStatusBadgeClass(status) {
        // First check if status is directly provided
        if (status) {
            switch(status.toLowerCase()) {
                case 'completed':
                    return 'badge-success';
                case 'in_progress':
                    return 'badge-warning';
                case 'pending':
                case 'todo':
                case 'not started':
                    return 'badge-info';
                default:
                    return 'badge-ghost';
            }
        }
        return 'badge-ghost';
    }
});
</script>

<style>
.calendar-container {
    width: 100%;
    min-width: 800px;
}

.day {
    min-height: 120px;
    min-width: 100px;
    position: relative;
    padding: 0;
    transition: all 0.2s ease;
    border: 1px solid transparent;
}

.day:hover {
    border-color: hsl(var(--p) / 0.3);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.day-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem;
    font-weight: 500;
    border-bottom: 1px solid rgba(var(--b3) / 0.1);
    background-color: rgba(var(--b2) / 0.3);
}

.day-number {
    height: 28px;
    width: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-weight: 600;
    transition: all 0.2s ease;
}

.is-today .day-number {
    background-color: hsl(var(--p));
    color: hsl(var(--pc));
}

.day-content {
    padding: 0.5rem;
    overflow-y: auto;
    max-height: calc(100% - 36px);
}

.task-item {
    margin-bottom: 0.3rem;
    border-radius: 4px;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    cursor: pointer;
    border-left: 3px solid transparent;
    background-color: hsl(var(--b1));
    transition: all 0.2s ease;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.task-item:hover {
    background-color: hsl(var(--b2));
    transform: translateY(-1px);
    box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
}

.task-item.priority-high {
    border-left-color: hsl(var(--er));
}

.task-item.priority-medium {
    border-left-color: hsl(var(--wa));
}

.task-item.priority-low {
    border-left-color: hsl(var(--in));
}

.current-month {
    background-color: hsl(var(--b1));
}

.prev-month, .next-month {
    background-color: hsl(var(--b2) / 0.5);
    opacity: 0.7;
}

.tab-content {
    display: block;
}

.tab-content.hidden {
    display: none;
}

.project-tag {
    display: inline-block;
    padding: 0.15rem 0.3rem;
    border-radius: 3px;
    font-size: 0.7rem;
    color: hsl(var(--pc));
    background-color: hsl(var(--p) / 0.7);
    white-space: nowrap;
}

/* Week View Styles */
.week-day {
    min-height: 200px;
}

.week-header {
    padding: 0.5rem;
    font-weight: 600;
    text-align: center;
    border-bottom: 1px solid rgba(var(--b3) / 0.2);
}

.week-date {
    font-size: 1.5rem;
    font-weight: bold;
}

/* Day View Styles */
.day-view-container {
    min-height: 500px;
}

.day-view-header {
    padding: 1rem;
    border-bottom: 1px solid rgba(var(--b3) / 0.2);
    background-color: hsl(var(--b2));
}

.day-view-title {
    font-size: 1.5rem;
    font-weight: bold;
}

.day-view-content {
    padding: 1rem;
}

.day-view-container.is-today {
    border-left: 4px solid hsl(var(--p));
}

.day-view-header.bg-primary\/10 {
    border-bottom: 2px solid hsl(var(--p) / 0.4);
}

.week-day.is-today {
    border: 2px solid hsl(var(--p) / 0.6);
    box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
}

.is-today {
    background-color: hsl(var(--p) / 0.5) !important;
    border: 3px solid hsl(var(--p)) !important;
    position: relative;
}

.is-today::after {
    content: 'TODAY';
    position: absolute;
    top: 2px;
    right: 5px;
    font-size: 8px;
    font-weight: 800;
    letter-spacing: 0.5px;
    background-color: hsl(var(--p));
    color: hsl(var(--pc));
    padding: 2px 4px;
    border-radius: 3px;
    opacity: 0.9;
}

.current-month.is-today {
    background: linear-gradient(45deg, hsl(var(--a) / 0.75), hsl(var(--af) / 0.8)) !important;
    box-shadow: 0 4px 3px rgba(0, 0, 0, 0.2);
}
}

.is-today .day-header {
    background-color: hsl(var(--p) / 0.7);
    border-bottom: 2px solid hsl(var(--p));
    color: hsl(var(--pc));
}

.is-today .day-number {
    background-color: hsl(var(--p));
    color: hsl(var(--pc));
    font-weight: 700;
}

.week-day.is-today {
    background: linear-gradient(135deg, hsl(var(--p) / 0.65), hsl(var(--p) / 0.45)) !important;
    border: 3px solid hsl(var(--p)) !important;
}

.week-day.is-today .week-header {
    background-color: hsl(var(--p) / 0.7);
    color: hsl(var(--pc));
}

.day-view-container.is-today {
    background-color: hsl(var(--p) / 0.35) !important;
    border-left: 6px solid hsl(var(--p));
}

@media (max-width: 768px) {
    .calendar-container {
        min-width: 100%;
    }
    .day {
        min-width: 0;
    }
}
</style>
@endsection 