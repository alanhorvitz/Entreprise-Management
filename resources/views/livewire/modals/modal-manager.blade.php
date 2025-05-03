<div>
    @if ($show)
        <div
            x-data="{ show: @entangle('show') }"
            x-show="show"
            x-on:keydown.escape.window="show = false"
            class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;">
            
            <!-- Modal backdrop -->
            <div 
                x-show="show" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black bg-opacity-50"
                x-on:click="show = false">
            </div>
            
            <!-- Modal content -->
            <div 
                x-show="show" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="relative mx-auto max-w-4xl my-10 bg-base-100 rounded-lg shadow-xl overflow-hidden p-6"
                style="max-height: calc(100vh - 120px); margin-top: 60px; margin-bottom: 60px;">
                
                <!-- Close button -->
                <button 
                    x-on:click="show = false"
                    class="absolute top-2 right-2 text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                
                <!-- Dynamic component -->
                <div class="max-h-[80vh] overflow-y-auto">
                    @if ($component)
                        @livewire($component, $arguments, key($component . '-' . rand()))
                    @endif
                </div>
            </div>
        </div>
    @endif
</div> 