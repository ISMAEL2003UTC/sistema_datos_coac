<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    |
    | Este es el mailer que se usará por defecto al enviar correos.
    |
    */

    'default' => env('MAIL_MAILER', 'sendgrid'), // cambiar a sendgrid

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    |
    | Aquí se configuran todos los mailers disponibles.
    |
    */

    'mailers' => [

        'sendgrid' => [
            'transport' => 'sendgrid',
            'api_key' => env('SENDGRID_API_KEY'), // usa la API Key en vez de SMTP
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    |
    | Correo y nombre que se usarán por defecto en todos los emails.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'firemaurofire@gmail.com'),
        'name' => env('MAIL_FROM_NAME', 'COAC'),
    ],

];
