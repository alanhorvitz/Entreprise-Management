<?php

namespace App\Livewire\Chat;

use App\Events\NewChatMessage;
use App\Models\Project;
use App\Models\ProjectsChatMessage;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class ChatManager extends Component
{
    public $projects = [];
    public $currentProject = null;
    public $messages = [];
    public $newMessage = '';
    public $listeners = [];
    
    public function mount($projectId = null)
    {
        try {
            $user = Auth::user();
            if ($user->hasRole('director')) {
                // For directors, get ALL projects
                $this->projects = Project::with(['supervisedBy', 'members'])
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->toArray();
            } elseif ($user->hasRole('supervisor')) {
                // For supervisors, get projects they supervise
                $this->projects = Project::with(['supervisedBy', 'members'])
                    ->where('supervised_by', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->toArray();
                
                // Debug supervisor projects
                \Log::info('Supervisor Projects Loaded', [
                    'user_id' => $user->id,
                    'projects_count' => count($this->projects),
                    'first_project' => $this->projects[0] ?? null
                ]);
            } else {
                // For employees and other users, get all projects where they are a member
                $employee = $user->employee;
                $this->projects = $employee ? $employee->projects()->with('supervisedBy')->get()->toArray() : [];
            }
            
            if ($projectId) {
                // If project ID is provided, try to find that project
                $project = Project::with(['supervisedBy', 'members'])->find($projectId);
                if ($project && ($user->hasRole('director') || 
                               ($user->hasRole('supervisor') && $project->supervised_by == $user->id) ||
                               in_array($project->id, array_column($this->projects, 'id')))) {
                    $this->currentProject = $project->toArray();
                } else {
                    // Fallback to first project if the specified project doesn't exist
                    // or the user doesn't have access
                    $this->currentProject = $this->projects[0] ?? null;
                }
            } else {
                // Get the first project or null if no projects
                $this->currentProject = $this->projects[0] ?? null;
            }
            
            // Debug current project
            if ($user->hasRole('supervisor')) {
                \Log::info('Current Project Set', [
                    'user_id' => $user->id,
                    'current_project' => $this->currentProject
                ]);
            }
            
            // If a project exists, get its chat messages
            if ($this->currentProject) {
                $this->loadMessages();
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error loading projects in ChatManager: ' . $e->getMessage());
            $this->projects = [];
            $this->currentProject = null;
        }
    }
    
    public function getListeners()
    {
        $listeners = [
            'echo-private:chat.project.' . ($this->currentProject['id'] ?? 0) . '.new-message' => 'receiveMessage',
            'refreshChat' => 'refreshChat',
        ];
        
        return $listeners;
    }
    
    public function receiveMessage($event)
    {
        // Only process if we're on the right project
        if ($this->currentProject && $this->currentProject['id'] == $event['projectId']) {
            // Add the new message to our messages array
            $this->messages[] = $event['message'];
            
            // Scroll to bottom
            $this->dispatch('newMessageReceived');
        }
    }
    
    public function loadMessages()
    {
        $messages = ProjectsChatMessage::where('project_id', $this->currentProject['id'])
                                     ->with('user')
                                     ->orderBy('created_at')
                                     ->get();
        
        // Convert to array to avoid serialization issues
        $this->messages = $messages->map(function($message) {
            $data = $message->toArray();
            $data['user'] = $message->user->toArray();
            return $data;
        })->toArray();
        
        $this->dispatch('chatMessagesLoaded');
    }
    
    public function changeProject($projectId)
    {
        try {
            $user = Auth::user();
            
            // For directors, always allow access to any project
            if ($user->hasRole('director')) {
                $project = Project::with('supervisedBy')->findOrFail($projectId);
                $this->currentProject = $project->toArray();
                if ($project->supervisedBy) {
                    $this->currentProject['supervisedBy'] = $project->supervisedBy->toArray();
                }
                $this->loadMessages();
                return;
            }
            
            // For other users, check permissions
            foreach ($this->projects as $project) {
                if ($project['id'] == $projectId) {
                    $this->currentProject = $project;
                    $this->loadMessages();
                    return;
                }
            }
            
            // If not found in our list, fetch it and check permission
            $project = Project::with('supervisedBy')->find($projectId);
            if ($project && (
                (Auth::user()->hasRole('supervisor') && $project->supervised_by === Auth::user()->id) ||
                Auth::user()->projectMembers->contains('id', $project->id)
            )) {
                $this->currentProject = $project->toArray();
                if ($project->supervisedBy) {
                    $this->currentProject['supervisedBy'] = $project->supervisedBy->toArray();
                }
                $this->loadMessages();
            }
        } catch (\Exception $e) {
            \Log::error('Error changing project in ChatManager: ' . $e->getMessage());
        }
    }
    
    public function canSendMessages()
    {
        $user = Auth::user();
        
        if ($user->hasRole('director')) {
            return true;
        }
        
        if ($user->hasRole('supervisor')) {
            // Debug the values we're comparing
            \Log::info('Supervisor Permission Check', [
                'user_id' => $user->id,
                'current_project' => $this->currentProject,
                'supervised_by' => $this->currentProject['supervised_by'] ?? null,
                'comparison_result' => $this->currentProject && 
                    $this->currentProject['supervised_by'] == $user->id
            ]);
            
            // Get the project from database to verify
            $project = Project::find($this->currentProject['id']);
            
            // Return true if this user is the supervisor
            return $project && $project->supervised_by == $user->id;
        }
        
        $employee = $user->employee;
        return $employee && collect($employee->projects()->pluck('projects.id'))
            ->contains($this->currentProject['id']);
    }

    public function sendMessage()
    {
        // Validate the message
        $this->validate([
            'newMessage' => 'required|string'
        ]);

        if (!$this->canSendMessages()) {
            abort(403, 'You are not allowed to chat in this project.');
        }

        // Create the message
        $chatMessage = ProjectsChatMessage::create([
            'project_id' => $this->currentProject['id'],
            'user_id' => Auth::id(),
            'message' => $this->newMessage
        ]);
        
        // Load the message with its user for broadcasting
        $chatMessage->load('user');
        
        // Format the message for broadcast
        $messageData = $chatMessage->toArray();
        $messageData['user'] = $chatMessage->user->toArray();
        
        // Broadcast to all users in this project
        event(new NewChatMessage($messageData, $this->currentProject['id']));
        
        // Clear the message input
        $this->newMessage = '';
        
        // Add the message to our local messages array
        $this->messages[] = $messageData;
        
        // Notify frontend to scroll down
        $this->dispatch('chatMessagesLoaded');
    }
    
    #[On('refreshChat')]
    public function refreshChat()
    {
        if ($this->currentProject) {
            $this->loadMessages();
        }
    }
    
    public function render()
    {
        return view('livewire.chat.chat-manager');
    }
} 