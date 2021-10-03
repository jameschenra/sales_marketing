<?php
// Aside menu
return [

    'items' => [
        // Dashboard
        [
            'title' => 'main.Dashboard',
            'root' => true,
            'icon' => 'assets/admin/media/svg/icons/Design/Layers.svg', // or can be 'flaticon-home' or any flaticon-*
            'page' => '/admin/dashboard',
            'new-tab' => false,
        ],

        [
            'title' => 'main.SEO',
            'root' => true,
            'icon' => 'assets/admin/media/svg/icons/Home/Library.svg',
            'page' => 'admin/seo',
            'visible' => 'preview',
        ],

        [
            'title' => 'main.Professional Management',
            'icon' => 'assets/admin/media/svg/icons/Home/Book-open.svg',
            'root' => true,
            'bullet' => 'dot',
            'submenu' => [
                [
                    'title' => 'main.Professionals',
                    'page' => 'admin/company'
                ],
                [
                    'title' => 'main.Categories',
                    'page' => 'admin/prof-category'
                ],
                [
                    'title' => 'main.Sub Categories',
                    'page' => 'admin/prof-sub-category'
                ],
            ]
        ],

        [
            'title' => 'main.Service Management',
            'icon' => 'assets/admin/media/svg/icons/Home/Book-open.svg',
            'root' => true,
            'bullet' => 'dot',
            'submenu' => [
                [
                    'title' => 'main.Services',
                    'page' => 'admin/service'
                ],
                [
                    'title' => 'main.Categories',
                    'page' => 'admin/service-category'
                ],
                [
                    'title' => 'main.Sub Categories',
                    'page' => 'admin/service-sub-category'
                ],
            ]
        ],

        [
            'title' => 'main.Transactions Management',
            'root' => true,
            'icon' => 'assets/admin/media/svg/icons/Home/Library.svg',
            'page' => 'admin/transaction',
            'visible' => 'preview',
        ],

        [
            'title' => 'main.Post Management',
            'icon' => 'assets/admin/media/svg/icons/Home/Book-open.svg',
            'root' => true,
            'bullet' => 'dot',
            'submenu' => [
                [
                    'title' => 'main.Post',
                    'page' => 'admin/post'
                ],
                [
                    'title' => 'main.Category',
                    'page' => 'admin/post-category'
                ],
                [
                    'title' => 'main.Policy',
                    'page' => 'admin/policy'
                ],
                [
                    'title' => 'main.Terms Condition Management',
                    'page' => 'admin/terms'
                ],
                [
                    'title' => 'main.Help Management',
                    'page' => 'admin/help'
                ],
                [
                    'title' => 'main.help type',
                    'page' => 'admin/help-type'
                ],
                [
                    'title' => 'main.How it works Management',
                    'page' => 'admin/how-it-works'
                ],
            ]
        ],

        [
            'title' => 'main.World of Professions',
            'root' => true,
            'icon' => 'assets/admin/media/svg/icons/Home/Library.svg',
            'page' => 'admin/world-of-profession',
            'visible' => 'preview',
        ],

        [
            'title' => 'main.User Management',
            'root' => true,
            'icon' => 'assets/admin/media/svg/icons/Home/Library.svg',
            'page' => 'admin/user',
            'visible' => 'preview',
        ],

        [
            'title' => 'main.Offer Management',
            'root' => true,
            'icon' => 'assets/admin/media/svg/icons/Home/Library.svg',
            'page' => 'admin/offer',
            'visible' => 'preview',
        ],

        [
            'title' => 'main.Loyalty Management',
            'root' => true,
            'icon' => 'assets/admin/media/svg/icons/Home/Library.svg',
            'page' => 'admin/loyalty',
            'visible' => 'preview',
        ],

        [
            'title' => 'main.Office Management',
            'root' => true,
            'icon' => 'assets/admin/media/svg/icons/Home/Library.svg',
            'page' => 'admin/office',
            'visible' => 'preview',
        ],

        [
            'title' => 'main.Plan Management',
            'root' => true,
            'icon' => 'assets/admin/media/svg/icons/Home/Library.svg',
            'page' => 'admin/plan',
            'visible' => 'preview',
        ],

        [
            'title' => 'main.Message Management',
            'icon' => 'assets/admin/media/svg/icons/Home/Book-open.svg',
            'root' => true,
            'bullet' => 'dot',
            'submenu' => [
                [
                    'title' => 'main.Profesional User',
                    'page' => 'admin/message/professional'
                ],
                [
                    'title' => 'main.general user',
                    'page' => 'admin/message/general'
                ],
            ]
        ],

        [
            'title' => 'main.Website Management',
            'root' => true,
            'icon' => 'assets/admin/media/svg/icons/Home/Library.svg',
            'page' => 'admin/app-setting',
            'visible' => 'preview',
        ],

        [
            'title' => 'main.Statistics',
            'root' => true,
            'icon' => 'assets/admin/media/svg/icons/Home/Library.svg',
            'page' => 'admin/statistic',
            'visible' => 'preview',
        ],

        [
            'title' => 'main.Recent Reviews',
            'root' => true,
            'icon' => 'assets/admin/media/svg/icons/Home/Library.svg',
            'page' => 'admin/review',
            'visible' => 'preview',
        ],
    ]

];
