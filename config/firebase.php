<?php
return [
    'server_key' => env('FIREBASE_SERVER_KEY', ''),
    'topic' => env('FIREBASE_DEFAULT_TOPIC', 'global'),
    'fcm_url' => env('FIREBASE_FCM_URL', 'https://fcm.googleapis.com/fcm/send'),
];


