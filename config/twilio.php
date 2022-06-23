<?php

return [
    'twilio' => [
        'default' => 'twilio',
        'connections' => [
            'twilio' => [
                /*
                |--------------------------------------------------------------------------
                | SID   
                |--------------------------------------------------------------------------
                |
                | Your Twilio Account SID #
                |
                */
                'sid' => env('TWILIO_SID', 'AC9e3071c4c5fb0005361452a20658fe3a'),

                /*
                |--------------------------------------------------------------------------
                | Access Token
                |--------------------------------------------------------------------------
                |
                | Access token that can be found in your Twilio dashboard
                |
                */
                'token' => env('TWILIO_TOKEN', 'b3633d4cff8b9839bbf931d6b31d41b7'),

                /*
                |--------------------------------------------------------------------------
                | From Number
                |--------------------------------------------------------------------------
                |
                | The Phone number registered with Twilio that your SMS & Calls will come from
                |
                */
                'from' => env('TWILIO_FROM', '+19893751887'),
            ],
        ],
    ],
];
