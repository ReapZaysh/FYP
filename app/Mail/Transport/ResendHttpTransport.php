<?php

namespace App\Mail\Transport;

use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;

class ResendHttpTransport extends AbstractTransport
{
    protected string $apiKey;

    public function __construct(string $apiKey)
    {
        parent::__construct();
        $this->apiKey = $apiKey;
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $html = $email->getHtmlBody();
        $text = $email->getTextBody();
        $subject = $email->getSubject();

        // Get To addresses
        $to = [];
        foreach ($email->getTo() as $address) {
            $to[] = $address->getAddress();
        }

        // Get From address
        $fromAddress = '';
        foreach ($email->getFrom() as $address) {
            $fromAddress = $address->getAddress();
            break; // We only support one sender
        }

        // If using Resend's free tier sandbox/onboarding, the sender MUST be onboarding@resend.dev
        // unless they have a verified custom domain. Let's check if they are using onboarding.
        if (str_contains($fromAddress, 'gmail.com') || str_contains($fromAddress, 'example.com') || empty($fromAddress)) {
            $fromAddress = 'onboarding@resend.dev';
        }

        $payload = [
            'from' => $fromAddress,
            'to' => $to,
            'subject' => $subject,
        ];

        if ($html) {
            $payload['html'] = $html;
        } elseif ($text) {
            $payload['text'] = $text;
        }

        $response = Http::withToken($this->apiKey)
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post('https://api.resend.com/emails', $payload);

        if (!$response->successful()) {
            throw new \Exception('Failed to send email via Resend API: ' . $response->body());
        }
    }

    public function __toString(): string
    {
        return 'resend-http';
    }
}
