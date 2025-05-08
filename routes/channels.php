<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Project;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.project.{projectId}', function ($user, $projectId) {
    try {
        // Directors always have access to all project chats
        if ($user->hasRole('director')) {
            return true;
        }

        // For supervisors, check if they supervise the project
        if ($user->hasRole('supervisor')) {
            return Project::where('id', $projectId)
                ->where('supervised_by', $user->id)
                ->exists();
        }

        // For other users, check if they are project members
        return $user->projectMembers->contains('id', $projectId);
    } catch (\Exception $e) {
        Log::error('Error in chat project channel authorization: ' . $e->getMessage());
        return false;
    }
});