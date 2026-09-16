<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MailjetService
{
    /**
     * Send email to all admin users
     *
     * @param string $subject
     * @param string $htmlContent
     * @return bool
     */
    public static function sendToAdmins($subject, $htmlContent)
    {
        $admins = \App\Models\Admin::all();
        $success = true;

        foreach ($admins as $admin) {
            if (!self::send($admin->email, $admin->name, $subject, $htmlContent)) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Send email using Mailjet API
     *
     * @param string|array $toEmail
     * @param string|array $toName
     * @param string $subject
     * @param string $htmlContent
     * @return bool
     */
    public static function send($toEmail, $toName, $subject, $htmlContent)
    {
        $status = Setting::get('mailjet_status');
        $apiKey = Setting::get('mailjet_api_key');
        $secretKey = Setting::get('mailjet_secret_key');
        $senderEmail = Setting::get('mailjet_sender_email');
        $siteName = Setting::get('site_name', 'Silva Stone');

        if (!$status || !$apiKey || !$secretKey || !$senderEmail) {
            Log::warning('Mailjet sending failed: Missing configuration or status is disabled.');
            return false;
        }

        try {
            $to = [];
            if (is_array($toEmail)) {
                foreach ($toEmail as $index => $email) {
                    $to[] = [
                        'Email' => $email,
                        'Name' => is_array($toName) ? ($toName[$index] ?? $email) : $toName
                    ];
                }
            } else {
                $to[] = [
                    'Email' => $toEmail,
                    'Name' => $toName
                ];
            }

            $response = Http::withBasicAuth($apiKey, $secretKey)
                ->post('https://api.mailjet.com/v3.1/send', [
                    'Messages' => [
                        [
                            'From' => [
                                'Email' => $senderEmail,
                                'Name' => $siteName
                            ],
                            'To' => $to,
                            'Subject' => $subject,
                            'HTMLPart' => $htmlContent,
                        ]
                    ]
                ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('Mailjet API Error: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('Mailjet Exception: ' . $e->getMessage());
            return false;
        }
    }
}
