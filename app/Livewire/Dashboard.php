<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();
        
        // Stats based on role
        if ($user->hasRole('director')) {
            $activeProjects = Project::whereIn('status', ['planning', 'in_progress'])->count();
            $completedTasks = Task::where('current_status', 'completed')->count();
            $pendingTasks = Task::whereIn('current_status', ['todo', 'in_progress'])->count();
            $teamMembers = User::count();
            $recentProjects = Project::with(['members'])
                ->latest()
                ->take(5)
                ->get();
        } elseif ($user->hasRole('supervisor')) {
            $activeProjects = Project::where('supervised_by', $user->id)
                ->whereIn('status', ['planning', 'in_progress'])
                ->count();
            $completedTasks = Task::whereIn('project_id', function($query) use ($user) {
                $query->select('id')->from('projects')->where('supervised_by', $user->id);
            })->where('current_status', 'completed')->count();
            $pendingTasks = Task::whereIn('project_id', function($query) use ($user) {
                $query->select('id')->from('projects')->where('supervised_by', $user->id);
            })->whereIn('current_status', ['todo', 'in_progress'])->count();
            $teamMembers = Project::where('supervised_by', $user->id)->count();
            $recentProjects = Project::with(['members'])
                ->where('supervised_by', $user->id)
                ->latest()
                ->take(5)
                ->get();
        } else {
            // For team_leader and employee
            if ($user->employee) {
                $projectIds = $user->employee->projects()->pluck('projects.id');
                
                $activeProjects = Project::whereIn('id', $projectIds)
                    ->whereIn('status', ['planning', 'in_progress'])
                    ->count();

                // Get tasks assigned to the user
                $completedTasks = Task::whereHas('taskAssignments', function($query) use ($user) {
                    $query->whereHas('employee', function($subQuery) use ($user) {
                        $subQuery->where('user_id', $user->id);
                    });
                })
                ->where('current_status', 'completed')
                ->count();

                $pendingTasks = Task::whereHas('taskAssignments', function($query) use ($user) {
                    $query->whereHas('employee', function($subQuery) use ($user) {
                        $subQuery->where('user_id', $user->id);
                    });
                })
                ->whereIn('current_status', ['todo', 'in_progress'])
                ->count();

                $teamMembers = $projectIds->count();
                
                $recentProjects = Project::with(['members'])
                    ->whereIn('id', $projectIds)
                    ->latest()
                    ->take(5)
                    ->get();
            } else {
                // If no employee record exists, set default values
                $activeProjects = 0;
                $completedTasks = 0;
                $pendingTasks = 0;
                $teamMembers = 0;
                $recentProjects = collect();
            }
        }
        
        // Pending approvals - only for director and supervisor
        if ($user->hasRole('director')) {
            $pendingApprovals = Task::with(['project', 'createdBy'])
                ->where('status', 'pending_approval')
                ->latest()
                ->take(2)
                ->get();
        } elseif ($user->hasRole('supervisor')) {
            $pendingApprovals = Task::with(['project', 'createdBy'])
                ->where('status', 'pending_approval')
                ->whereIn('project_id', function($query) use ($user) {
                    $query->select('id')->from('projects')->where('supervised_by', $user->id);
                })
                ->latest()
                ->take(2)
                ->get();
        } else {
            $pendingApprovals = collect();
        }
        
        // Tasks due soon - filtered by project access and assignments
        if ($user->hasRole('director')) {
            $tasksDueSoon = Task::with(['project', 'createdBy', 'assignedUsers'])
                ->where('due_date', '>=', now())
                ->where('due_date', '<=', now()->addDays(7))
                ->whereIn('current_status', ['todo', 'in_progress'])
                ->latest()
                ->take(5)
                ->get();
        } elseif ($user->hasRole('supervisor')) {
            $tasksDueSoon = Task::with(['project', 'createdBy', 'assignedUsers'])
                ->where('due_date', '>=', now())
                ->where('due_date', '<=', now()->addDays(7))
                ->whereIn('current_status', ['todo', 'in_progress'])
                ->whereIn('project_id', function($query) use ($user) {
                    $query->select('id')->from('projects')->where('supervised_by', $user->id);
                })
                ->latest()
                ->take(5)
                ->get();
        } else {
            // For team_leader and employee - only show their assigned tasks
            $tasksDueSoon = Task::with(['project', 'createdBy', 'assignedUsers'])
                ->where('due_date', '>=', now())
                ->where('due_date', '<=', now()->addDays(7))
                ->whereIn('current_status', ['todo', 'in_progress'])
                ->whereHas('taskAssignments', function($query) use ($user) {
                    $query->whereHas('employee', function($subQuery) use ($user) {
                        $subQuery->where('user_id', $user->id);
                    });
                })
                ->latest()
                ->take(5)
                ->get();
        }
        
        return view('livewire.dashboard', [
            'activeProjects' => $activeProjects,
            'completedTasks' => $completedTasks,
            'pendingTasks' => $pendingTasks,
            'teamMembers' => $teamMembers,
            'recentProjects' => $recentProjects,
            'pendingApprovals' => $pendingApprovals,
            'tasksDueSoon' => $tasksDueSoon,
            'canApproveTask' => $user->hasRole(['director', 'supervisor']),
        ]);
    }

    public function approveTask($taskId)
    {
        $task = Task::find($taskId);
        if ($task) {
            $task->update(['status' => 'approved']);
        }
    }

    public function rejectTask($taskId) 
    {
        $task = Task::find($taskId);
        if ($task) {
            $task->update(['status' => 'rejected']);
        }
    }
}
