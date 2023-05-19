<?php
return [
    [
        "icon" => "home",
        "title" => "Dashboard",
        "child" => 'dashboard'
    ],
    [
        "icon" => "tag",
        "title" => "Categories",
        "child" => [
            [
                "child" => "module.categories.home",
                "title" => "Categories"
            ],[
                "child" => "module.categories.add",
                "title" => "Add Category"
            ]
        ]
    ],
    // [
    //     "icon" => "users",
    //     "title" => "Parties",
    //     "child" => [
    //         [
    //             "child" => "module.clients.home",
    //             "title" => "View Clients"
    //         ], [
    //             "child" => "module.users.home",
    //             "title" => "View Users"
    //         ],
    //     ]
    // ],
    // [
    //     "icon" => "users",
    //     "title" => "Services",
    //     "child" => "module.services.home"
    // ],
    // [
    //     "icon" => "clipboard",
    //     "title" => "Purchases",
    //     "child" => [
    //         [
    //             "child" => "module.purchaseOrders.home",
    //             "title" => "History"
    //         ], [
    //             "child" => "module.purchaseOrders.add",
    //             "title" => "Add Purchase Order",
    //             'subscription'=>1
    //         ],
    //     ]
    // ],

];
