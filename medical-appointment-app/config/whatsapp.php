<?php

return [
    'default' => env('WHATSAPP_DRIVER', 'log'),

    'drivers' => [
        'twilio' => [
            'sid' => env('TWILIO_SID'),
            'token' => env('TWILIO_AUTH_TOKEN'),
            'from' => env('TWILIO_WHATSAPP_FROM'),
        ],

        'meta' => [
            'token' => env('WHATSAPP_TOKEN'),
            'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
        ],

        'evolution' => [
            'url' => env('EVOLUTION_API_URL'),
            'key' => env('EVOLUTION_API_KEY'),
            'instance' => env('EVOLUTION_INSTANCE'),
        ],
    ],
];
