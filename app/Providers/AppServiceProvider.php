<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Dashboard Components
        \Livewire\Livewire::component('dashboard', \App\Livewire\Dashboard\Dashboard::class);
        
        // Project Components
        \Livewire\Livewire::component('projects.create', \App\Livewire\Projects\CreateProject::class);
        \Livewire\Livewire::component('projects.edit', \App\Livewire\Projects\EditProject::class);
        \Livewire\Livewire::component('projects.list', \App\Livewire\Projects\ProjectList::class);
        \Livewire\Livewire::component('projects.details', \App\Livewire\Projects\ProjectDetails::class);
        \Livewire\Livewire::component('projects.add-member', \App\Livewire\Projects\AddMemberModal::class);
        
        // Task Components
        \Livewire\Livewire::component('tasks.list', \App\Livewire\Tasks\TaskList::class);
        \Livewire\Livewire::component('tasks.create', \App\Livewire\Tasks\TaskCreate::class);
        \Livewire\Livewire::component('tasks.edit', \App\Livewire\Tasks\TaskEdit::class);
        \Livewire\Livewire::component('tasks.show', \App\Livewire\Tasks\TaskShow::class);
        \Livewire\Livewire::component('tasks.delete', \App\Livewire\Tasks\TaskDelete::class);
        \Livewire\Livewire::component('tasks.create-page', \App\Livewire\Tasks\CreateTaskPage::class);
        
        // Notification Components
        \Livewire\Livewire::component('notifications.manager', \App\Livewire\Notifications\NotificationManager::class);
        
        // Modal Components
        \Livewire\Livewire::component('modals.manager', \App\Livewire\Modals\ModalManager::class);
        
        // Chat Components
        \Livewire\Livewire::component('chat.manager', \App\Livewire\Chat\ChatManager::class);
    }
}
