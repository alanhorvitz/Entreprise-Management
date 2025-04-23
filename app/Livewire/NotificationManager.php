<?php

namespace App\Livewire;

use Livewire\Component;

class NotificationManager extends Component
{
    public $notifications = [];
    
    protected $listeners = ['notify' => 'addNotification'];
    
    public function addNotification($notification)
    {
        $notification['id'] = uniqid();
        $this->notifications[] = $notification;
    }
    
    public function removeNotification($id)
    {
        $this->notifications = array_filter($this->notifications, function($notification) use ($id) {
            return $notification['id'] !== $id;
        });
    }
    
    public function render()
    {
        return view('livewire.notification-manager');
    }
} 