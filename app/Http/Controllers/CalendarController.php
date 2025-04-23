<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\Project;
use App\Models\ProjectMember;

class CalendarController extends Controller
{
    /**
     * Display the calendar view showing tasks from projects the user is part of.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $userId = auth()->id();
        
        // Get projects where the user is a member
        $userProjectIds = ProjectMember::where('user_id', $userId)
            ->pluck('project_id')
            ->toArray();
            
        // Also include projects created or supervised by the user
        $createdProjectIds = Project::where('created_by', $userId)
            ->orWhere('supervised_by', $userId)
            ->pluck('id')
            ->toArray();
            
        // Combine all project IDs the user is associated with
        $allUserProjectIds = array_unique(array_merge($userProjectIds, $createdProjectIds));
        
        // Get all tasks from these projects
        $allTasks = Task::with('project')
            ->whereIn('project_id', $allUserProjectIds)
            ->get();
            
        // Get task assignments for the authenticated user
        $taskAssignments = TaskAssignment::where('user_id', $userId)->get();
        
        // Extract the task IDs from assignments
        $assignedTaskIds = $taskAssignments->pluck('task_id')->toArray();
        
        // Mark each task as assigned or not assigned to the current user
        foreach ($allTasks as $task) {
            $task->assigned_to_user = in_array($task->id, $assignedTaskIds);
        }
        
        // Get all projects for the filter dropdown
        $projects = Project::whereIn('id', $allUserProjectIds)->get();
        
        return view('calendar.index', [
            'tasks' => $allTasks,
            'projects' => $projects,
            'assignedTaskIds' => $assignedTaskIds
        ]);
    }
} 
