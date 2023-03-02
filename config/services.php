<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

    'agora' => [
        'appId' => env('AGORA_APP_ID'),
        'certificate' => env('AGORA_CERTIFICATE'),
        'customerId' => env('AGORA_CUSTOMER_ID'),
        'customerSecret' => env('AGORA_CUSTOMER_SECRET')
    ],

    'aws' => [
        'access_key' => env('AWS_ACCESS_KEY_ID'),
        'secret_key' => env('AWS_SECRET_ACCESS_KEY'),
        'default_region' => env('AWS_DEFAULT_REGION'),
        'bucket' => env('AWS_BUCKET')
    ],

    'firebase' => [
        'production' => [
            "apiKey" => "AIzaSyAl9bhygip8ZJWV-wRCww-L75FBZnMcfNg",
            "authDomain" => "researchers-live.firebaseapp.com",
            'databaseURL' => "https://researchers-live-default-rtdb.firebaseio.com/",
            "projectId" => "researchers-live",
            "storageBucket" => "researchers-live.appspot.com",
            "messagingSenderId" => "1048017936285",
            "appId" => "1:1048017936285:web:12b70ba5b23e02f7939974"
        ],
        'local' => [
            'apiKey' => "AIzaSyA5mhLDBmZCq12c_lEsrcR3ljiSbm_oalk",
            'authDomain' => "mr-paradise-1558815231649.firebaseapp.com",
            'databaseURL' => "https://researchers-live-default-rtdb.firebaseio.com/",
            'projectId' => "mr-paradise-1558815231649",
            'storageBucket' => "mr-paradise-1558815231649.appspot.com",
            'messagingSenderId' => "171438813430",
            'appId' => "1:171438813430:web:558f7c17b18f08b3d27cd7",
            'measurementId' => "G-XPS7KXKLGH"
        ],
        'local1' => [
            'apiKey' => "AIzaSyDlyK68EwzOrB1bmHCaOui2-P2D7hn14dY",
            'authDomain' => "rl-local.firebaseapp.com",
            'databaseURL' => "https://rl-local-default-rtdb.firebaseio.com/",
            'projectId' => "rl-local",
            'storageBucket' => "rl-local.appspot.com",
            'messagingSenderId' => "111889868000",
            'appId' => "1:111889868000:web:64b45adbef4a308da69c85",
        ],
        'staging1' => [
            "apiKey" => "AIzaSyAl9bhygip8ZJWV-wRCww-L75FBZnMcfNg",
            "authDomain" => "researchers-live.firebaseapp.com",
            'databaseURL' => "https://researchers-live-default-rtdb.firebaseio.com/",
            "projectId" => "researchers-live",
            "storageBucket" => "researchers-live.appspot.com",
            "messagingSenderId" => "1048017936285",
            "appId" => "1:1048017936285:web:12b70ba5b23e02f7939974"
        ],
        'staging' => [
            "apiKey" => "AIzaSyB1u5nwMbfzlZenAoIa0Wy7Xjull5wHgvg",
            "authDomain" => "researchers-live-14952.firebaseapp.com",
            'databaseURL' => "https://researchers-live-14952-default-rtdb.firebaseio.com",
            "projectId" => "researchers-live-14952",
            "storageBucket" => "researchers-live-14952.appspot.com",
            "messagingSenderId" => "1024672023739",
            "appId" => "1:1024672023739:web:ef14072c5af9aab866959e"
        ]
    ]
];
