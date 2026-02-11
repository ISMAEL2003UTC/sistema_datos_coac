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
            'api_key' => env('SENDGRID_API_KEY'),
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],

    ],

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'firemaurofire@gmail.com'),
        'name' => env('MAIL_FROM_NAME', 'COAC'),
    ],

];
