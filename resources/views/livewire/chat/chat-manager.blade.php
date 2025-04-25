<div class="flex flex-col h-full bg-base-100 gap-4">
    <!-- Chat header with project selector -->
    <div class="bg-base-200 rounded-lg shadow-md p-4">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold">Project Chat</h2>
            
            <!-- Project selector dropdown -->
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn min-w-44 flex justify-between">
                    <span class="truncate">{{ $currentProject ? $currentProject['name'] : 'No Projects' }}</span>
                    <span class="iconify" data-icon="heroicons:chevron-down"></span>
                </div>
                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52 max-h-60 overflow-y-auto">
                    @forelse($projects as $project)
                        <li>
                            <a wire:click.prevent="changeProject({{ $project['id'] }})" class="flex items-start gap-2 {{ $currentProject && $currentProject['id'] === $project['id'] ? 'active' : '' }}">
                                <div class="flex-1 min-w-0">
                                    <div class="truncate {{ $project['supervised_by'] == Auth::id() ? 'font-bold text-primary' : '' }}">
                                        {{ $project['name'] }}
                                    </div>
                                </div>
                            </a>
                        </li>
                    @empty
                        <li><div class="p-2 text-gray-500">No projects available</div></li>
                    @endforelse
                </ul>
            </div>
        </div>
        
        @if($currentProject)
        <div class="mt-2 text-sm">
            @if(isset($currentProject['supervisedBy']))
                <span class="text-primary font-medium">Supervisor: {{ $currentProject['supervisedBy']['name'] }}</span>
            @endif
            <span class="text-sm text-gray-500">{{ $currentProject['description'] }}</span>
        </div>
        @endif
    </div>
    
    <!-- Chat messages area -->
    <div class="flex-1 overflow-y-auto bg-base-100 rounded-lg shadow-md p-4 flex flex-col gap-4 min-h-[400px]" id="chat-messages">
        @if($currentProject)
            @forelse($messages as $message)
                <div class="chat {{ $message['user_id'] === Auth::id() ? 'chat-end' : 'chat-start' }}">
                    <div class="chat-image avatar">
                        <div class="w-10 rounded-full">
                            <div class="bg-primary text-primary-content w-full h-full flex items-center justify-center">
                                {{ substr($message['user']['first_name'], 0, 1) }}{{ substr($message['user']['last_name'], 0, 1) }}
                            </div>
                        </div>
                    </div>
                    <div class="chat-header">
                        <span class="font-bold">{{ $message['user']['first_name'] }} {{ $message['user']['last_name'] }}</span>
                        <time class="text-xs opacity-50">{{ \Carbon\Carbon::parse($message['created_at'])->format('M j, H:i') }}</time>
                    </div>
                    <div class="chat-bubble {{ $message['user_id'] === Auth::id() ? 'chat-bubble-primary' : 'chat-bubble-secondary' }}">
                        {{ $message['message'] }}
                    </div>
                </div>
            @empty
                <div class="flex items-center justify-center h-full">
                    <div class="text-center text-gray-500">
                        <p class="text-xl mb-2">No messages yet</p>
                        <p>Start the conversation by sending a message!</p>
                    </div>
                </div>
            @endforelse
        @else
            <div class="flex items-center justify-center h-full">
                <div class="text-center text-gray-500">
                    <p class="text-xl mb-2">No project selected</p>
                    <p>Select a project to start chatting</p>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Message input -->
    @if($currentProject)
    <div class="bg-base-200 rounded-lg shadow-md p-4">
        <form wire:submit.prevent="sendMessage" class="flex gap-2">
            <input 
                type="text" 
                wire:model="newMessage"
                placeholder="Type your message here..." 
                class="input input-bordered w-full" 
                required 
                autofocus
            >
            <button type="submit" class="btn btn-primary">
                <span class="iconify" data-icon="heroicons:paper-airplane"></span>
                Send
            </button>
        </form>
    </div>
    @endif
</div>

@script
    document.addEventListener('livewire:initialized', () => {
        // Scroll to bottom of chat messages on page load and after messages are loaded
        const scrollToBottom = () => {
            const chatContainer = document.getElementById('chat-messages');
            if (chatContainer) {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        };
        
        // Scroll initially
        scrollToBottom();
        
        // Listen for new messages loaded
        $wire.on('chatMessagesLoaded', () => {
            setTimeout(scrollToBottom, 50);
        });
        
        // Poll for new messages every 10 seconds to keep chat updated
        setInterval(() => {
            $wire.dispatch('refreshChat');
        }, 10000);
    });
@endscript 