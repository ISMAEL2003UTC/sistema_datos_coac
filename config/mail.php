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

    'default' => env('MAIL_MAILER', 'smtp'),

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    |
    | Aquí se configuran todos los mailers disponibles.
    |
    */

    'mailers' => [

        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', 'smtp.sendgrid.net'),
            'port' => env('MAIL_PORT', 587),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username' => env('MAIL_USERNAME', 'apikey'), // siempre 'apikey' para SendGrid
            'password' => env('MAIL_PASSWORD'),           // tu SendGrid API Key
            'timeout' => null,
            'auth_mode' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
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
