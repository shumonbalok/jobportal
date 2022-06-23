<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Class OtpService.
 */
class OtpService
{
    private $otpUrl = '';
    private $otpServerKey = '';
    private $otpSenderId = '';
    private $otpType = '';

    public function __construct()
    {
        $this->otpServerKey = config('otp.api_key');
        $this->otpUrl = config('otp.otp_url');
        $this->otpSenderId = config('otp.senderid');
        $this->otpType = config('otp.type');
    }

    public function sendOtpNotification(string $contacts, string $msg)
    {
        $body = [
                'contacts' => $contacts,
                'msg' => $msg,
                'api_key' => $this->otpServerKey,
                'senderid' => $this->otpSenderId,
                'type' => $this->otpType
        ];
        Log::info('Sending Otp data: ' . json_encode($body));
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post($this->otpUrl, $body);
        Log::info('Otp Notification Response: ' . $response->body());
        return $response->successful();
    }

}
