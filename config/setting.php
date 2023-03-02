<?php

return [
    's3_url' => 'https://s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . env('AWS_BUCKET') . '/',
    'event_recording_folder_name' => 'eventRecordings',
    'user_status' => [
        1 => 'active',
        0 => 'inactive'
    ],
    'profile_status' => [
        'approved' => 'Approved',
        'decline' => 'Declined'
    ],
    'event_status' => [
        'pending' => 'Draft',
        'publish' => 'Upcoming',
        'finish' => 'Archive'
    ],
    'video_status' => [
        'pending' => 'Draft',
        'publish' => 'Publish',
    ],
    'wp_url' => 'https://zeroguess.com/cloudApp/rl/public',
    'terms_url' => 'https://zeroguess.com/cloudApp/rl/public/terms-conditions/',
    'privacy_url' => 'https://zeroguess.com/cloudApp/rl/public/privacy-policy/',
    'contact_url' => 'https://zeroguess.com/cloudApp/rl/public/contact/',
    'faq_url' => 'https://zeroguess.com/cloudApp/rl/public/faq/',
    'about_url' => 'https://zeroguess.com/cloudApp/rl/public/about/',
    'how_did_you_hear_options' => [
        'Internet Search',
        'Professional Society Communication',
        'Colleague',
    ],
    'area_of_interest_options' => [
        'Science',
        'Computer Science',
        'Tech',
        'Medical'
    ],
    'subscription' => [
        'pricing' => [
            'rl_yearly' => 'price_1IhqY3Acvfvfr0ERK2R61bYl'
        ],
        'coupon' => [
            'discounted_subscription' => 'xpMUeCGp'
        ]
    ]
];

