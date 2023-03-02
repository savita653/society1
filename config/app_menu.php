<?php

return [
    'super_admin' => [
        'menu' => [
            // [
            //     "url" => '/super-admin',
            //     "name" => "Dashboard",
            //     "icon" => "activity",
            //     "slug" => "home",
            // ],
            // [
            //     "navheader" => "User Management",
            //     "slug" => "",
            //     "icon" => "",
            // ],
            // [
            //     "url" => 'super-admin/admins',
            //     "name" => "Admins",
            //     "icon" => "users",
            //     "slug" => "admins.index",
            // ],
            // [
            //     "url" => "super-admin/presenters",
            //     "name" => "Presenters",
            //     "icon" => "users",
            //     "slug" => "presenters.index"
            // ],
            // [
            //     "url" => "super-admin/users",
            //     "name" => "Subscribers",
            //     "icon" => "users",
            //     "slug" => "users.index"
            // ],
            // [
            //     "navheader" => "Media",
            //     "slug" => "",
            //     "icon" => "",
            // ],
            // [
            //     "url" => "app/manage/events",
            //     "name" => "Events",
            //     "icon" => "calendar",
            //     "slug" => "events.index"
            // ],
            // [
            //     "url" => "app/manage/videos",
            //     "name" => "Videos",
            //     "icon" => "video",
            //     "slug" => "videos.index"
            // ],
            // [
            //     "navheader" => "Activities",
            //     "slug" => "",
            //     "icon" => "",
            // ],
            // [
            //     "url" => "super-admin/user-activities",
            //     "name" => "User Activities",
            //     "icon" => "activity",
            //     "slug" => "super-admin.user_activities"
            // ],
            [
                "url" => '/users',
                "name" => "Users",
                "icon" => "",
                "slug" => "Users",
            ],
            [
                "url" => '/societies',
                "name" => "Society Listing",
                "icon" => "",
                "slug" => "Societies",
            ],
            [
                "url" => '/houses',
                "name" => "House Listing",
                "icon" => "",
                "slug" => "houses",
            ],
            [
                "url" => '/logout',
                "name" => "Logout",
                "icon" => "",
                "slug" => "logout",
            ],
        ]
    ],
    'admin' => [
        'menu' => [
            
            [
                "url" => '/account',
                "name" => "Account",
                "icon" => "user",
                "slug" => "account",
            ],
            [
                "navheader" => "Media",
                "slug" => "",
                "icon" => "",
            ],
            [
                "url" => "app/manage/events",
                "name" => "Events",
                "icon" => "calendar",
                "slug" => "events.index"
            ],
            [
                "url" => "app/manage/videos",
                "name" => "Videos",
                "icon" => "video",
                "slug" => "videos.index"
            ]
        ]
    ],
    'presenter' => [
        'menu' => [
            [
                "url" => '/',
                "name" => "Dashboard",
                "icon" => "activity",
                "slug" => "home",
            ],
            [
                "url" => '/account',
                "name" => "Account",
                "icon" => "user",
                "slug" => "account",
            ],
            [
                "navheader" => "Media",
                "slug" => "",
                "icon" => "",
            ],
            [
                "url" => "presenter/events",
                "name" => "Events",
                "icon" => "calendar",
                "slug" => "presenter.events.index"
            ],
        ]
    ],
    'user' => [
        'menu' => [
            [
                "url" => '/',
                "name" => "Dashboard",
                "icon" => "activity",
                "slug" => "home",
            ],
            [
                "url" => '/account',
                "name" => "Account",
                "icon" => "user",
                "slug" => "account",
            ],
        ]
    ]
];
