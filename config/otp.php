<?php
return [
    'api_key' => env('OTP_KEY', 'C3000004628ca48973f732.79028244'),
    'otp_url' => env('OTP_URL', 'https://sms.mram.com.bd/smsapi'),
    'senderid' => env('OTP_SENDER_ID', '8809601002804'),
    'type' => env('OTP_TYPE', 'text/unicode'),
];
