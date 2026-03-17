<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    public function sendToUser(User $user, $title, $body, $data = [])
    {
        if (!$user->fcm_token) {
            return false;
        }

        try {
            // Implement FCM or Pusher push notification
            Log::info("Push notification sent to {$user->id}: {$title} - {$body}");
            
            return true;
        } catch (\Exception $e) {
            Log::error("Push notification failed: " . $e->getMessage());
            return false;
        }
    }
}