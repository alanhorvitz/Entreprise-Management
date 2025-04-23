<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\Project;

class CalendarController extends Controller
{
    /**
     * Display the read-only calendar view showing tasks.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get task assignments for the authenticated user
        $taskAssignments = TaskAssignment::where('user_id', auth()->id())->get();
        
        // Extract the task IDs from assignments
        $taskIds = $taskAssignments->pluck('task_id');
        
        // Get the actual tasks with project information
        $tasks = Task::with('project')
            ->whereIn('id', $taskIds)
            ->get();
        
        // Get all projects for the filter dropdown
        $projects = Project::all();
        
        return view('calendar.index', [
            'tasks' => $tasks,
            'projects' => $projects
        ]);
    }
} 
