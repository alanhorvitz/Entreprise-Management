<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

class Dashboard extends Component
{
    public function render()
    {
        $activeProjects = Project::whereIn('status', ['planning', 'in_progress'])->count();
        
        $completedTasks = Task::where('current_status', 'completed')->count();
        
        $pendingTasks = Task::whereIn('current_status', ['todo', 'in_progress'])->count();
        
        $teamMembers = User::count();
        
        $recentProjects = Project::with(['members'])
            ->latest()
            ->take(5)
            ->get();
            
        $pendingApprovals = Task::with(['project', 'createdBy'])
            ->where('status', 'pending_approval')
            ->latest()
            ->take(2)
            ->get();
            
        $tasksDueSoon = Task::with(['project', 'assignedUsers'])
            ->whereIn('current_status', ['todo', 'in_progress'])
            ->whereNotNull('due_date')
            ->where('due_date', '>=', Carbon::now())
            ->where('due_date', '<=', Carbon::now()->addDays(7))
            ->orderBy('due_date')
            ->take(3)
            ->get();

        return view('livewire.dashboard', [
            'activeProjects' => $activeProjects,
            'completedTasks' => $completedTasks,
            'pendingTasks' => $pendingTasks,
            'teamMembers' => $teamMembers,
            'recentProjects' => $recentProjects,
            'pendingApprovals' => $pendingApprovals,
            'tasksDueSoon' => $tasksDueSoon
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
