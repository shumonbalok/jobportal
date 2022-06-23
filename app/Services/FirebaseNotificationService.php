<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\This;
/**
 * Class FirebaseNotificationService.
 */
class FirebaseNotificationService
{
    private $fcmUrl = '';
    private $firebaseServerKey = '';
    private $topic = '';

    public function __construct()
    {
        $this->firebaseServerKey = config('firebase.server_key');
        $this->fcmUrl = config('firebase.fcm_url');
        $this->topic = config('firebase.topic');
    }

    public function sendNotification(string $title, string $description)
    {
        $body = [
            'to' => '/topics/' . $this->topic,
            'notification' => [
                'title' => $title,
                'body' => $description
            ],
        ];

        Log::info('Sending notification data: ' . json_encode($body));

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $this->firebaseServerKey,
            'Content-Type' => 'application/json'
        ])->post($this->fcmUrl, $body);

        Log::info('Firebase Notification Response: ' . $response->body());

        return $response->successful();
    }
}
