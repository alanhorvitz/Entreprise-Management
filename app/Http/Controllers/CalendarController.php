<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\RepetitiveTask;

class CalendarController extends Controller
{
    /**
     * Display the calendar view showing tasks from projects the user is part of.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();
        
        // Initialize tasks query
        $tasksQuery = Task::with(['project', 'repetitiveTask']);
        
        // Apply role-based visibility rules
        if ($user->hasRole('director')) {
            // Directors can see all tasks
            $allTasks = $tasksQuery->get();
        } elseif ($user->hasRole('supervisor')) {
            // Supervisors can see all tasks in projects they supervise
            $allTasks = $tasksQuery->whereIn('project_id', function($query) use ($user) {
                $query->select('id')
                    ->from('projects')
                    ->where('supervised_by', $user->id);
            })->get();
        } else {
            // Regular users can only see tasks assigned to them
            $allTasks = $tasksQuery->whereHas('taskAssignments', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();
        }
        
        // Get task assignments for the authenticated user
        $taskAssignments = TaskAssignment::where('user_id', $user->id)->get();
        
        // Get IDs of repetitive tasks
        $repetitiveTaskIds = RepetitiveTask::pluck('task_id')->toArray();
        
        // Extract the task IDs from assignments
        $assignedTaskIds = $taskAssignments->pluck('task_id')->toArray();
        
        // Mark each task as assigned or not assigned to the current user
        // Also add a flag for repetitive tasks
        foreach ($allTasks as $task) {
            $task->assigned_to_user = in_array($task->id, $assignedTaskIds);
            $task->is_repetitive = $task->repetitiveTask !== null;
            
            // Add repetition info if this is a repetitive task
            if ($task->is_repetitive) {
                $task->repetition_rate = $task->repetitiveTask->repetition_rate;
            }
        }
        
        // Get projects for the filter dropdown based on role
        if ($user->hasRole('director')) {
            $projects = Project::all();
        } elseif ($user->hasRole('supervisor')) {
            $projects = Project::where('supervised_by', $user->id)->get();
        } else {
            $projects = Project::whereHas('members', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();
        }
        
        return view('calendar.index', [
            'tasks' => $allTasks,
            'projects' => $projects,
            'assignedTaskIds' => $assignedTaskIds
        ]);
    }
} 
