<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $provider;
    protected ?string $twilioSid;
    protected ?string $twilioToken;
    protected ?string $twilioFrom;

    protected ?string $evolutionUrl;
    protected ?string $evolutionKey;
    protected ?string $evolutionInstance;

    public function __construct()
    {
        $this->provider = config('whatsapp.default', 'log');
        
        $this->twilioSid = config('whatsapp.drivers.twilio.sid');
        $this->twilioToken = config('whatsapp.drivers.twilio.token');
        $this->twilioFrom = config('whatsapp.drivers.twilio.from');

        $this->evolutionUrl = config('whatsapp.drivers.evolution.url');
        $this->evolutionKey = config('whatsapp.drivers.evolution.key');
        $this->evolutionInstance = config('whatsapp.drivers.evolution.instance');
    }

    public function sendMessage(string $to, string $message): bool
    {
        $phone = $this->normalizePhone($to);

        return match ($this->provider) {
            'twilio'    => $this->sendViaTwilio($phone, $message),
            'meta'      => $this->sendViaMeta($phone, $message),
            'evolution' => $this->sendViaEvolution($phone, $message),
            default     => $this->logMessage($phone, $message),
        };
    }

    public function sendTemplate(string $to, string $contentSid, array $variables): bool
    {
        $phone = $this->normalizePhone($to);

        return match ($this->provider) {
            'twilio' => $this->sendTemplateViaTwilio($phone, $contentSid, $variables),
            default  => $this->logMessage($phone, "Template: $contentSid | Data: " . json_encode($variables)),
        };
    }

    protected function sendViaTwilio(string $to, string $message): bool
    {
        return $this->executeTwilioRequest([
            'From' => "whatsapp:{$this->twilioFrom}",
            'Body' => $message,
            'To'   => "whatsapp:{$to}",
        ]);
    }

    protected function sendTemplateViaTwilio(string $to, string $contentSid, array $variables): bool
    {
        return $this->executeTwilioRequest([
            'From' => "whatsapp:{$this->twilioFrom}",
            'To'   => "whatsapp:{$to}",
            'ContentSid' => $contentSid,
            'ContentVariables' => json_encode($variables),
        ]);
    }

    protected function executeTwilioRequest(array $data): bool
    {
        try {
            $response = Http::asForm()
                ->withBasicAuth($this->twilioSid, $this->twilioToken)
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$this->twilioSid}/Messages.json", $data);

            if (!$response->successful()) {
                Log::error('Twilio WhatsApp error response: ' . $response->body());
            }

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Twilio WhatsApp Exception: ' . $e->getMessage());
            return false;
        }
    }

    protected function sendViaMeta(string $to, string $message): bool
    {
        $phoneNumberId = config('whatsapp.drivers.meta.phone_number_id');
        $token = config('whatsapp.drivers.meta.token');

        try {
            $response = Http::withToken($token)
                ->post("https://graph.facebook.com/v22.0/{$phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $to,
                    'type' => 'text',
                    'text' => ['body' => $message],
                ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Meta WhatsApp error: ' . $e->getMessage());
            return false;
        }
    }

    protected function sendViaEvolution(string $to, string $message): bool
    {
        if (!$this->evolutionUrl || !$this->evolutionKey || !$this->evolutionInstance) {
            Log::error('Evolution API configuration missing');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'apikey' => $this->evolutionKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->evolutionUrl}/message/sendText/{$this->evolutionInstance}", [
                'number' => $to,
                'text' => $message,
                'linkPreview' => false,
            ]);

            if (!$response->successful()) {
                Log::error('Evolution API error response: ' . $response->body());
            }

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Evolution API Exception: ' . $e->getMessage());
            return false;
        }
    }

    protected function logMessage(string $to, string $message): bool
    {
        Log::info('[WhatsApp Mock] To: ' . $to . ' | Message: ' . $message);
        return true;
    }

    protected function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Formato para México: Si tiene 10 dígitos, agregamos 52 + 1 (móvil)
        if (strlen($phone) === 10) {
            $phone = '521' . $phone;
        }
        
        return $phone;
    }
}
