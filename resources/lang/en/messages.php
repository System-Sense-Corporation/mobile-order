<?php

return [
    'app' => [
        'name' => 'Mobile Order',
        'language' => [
            'label' => 'Language',
            'options' => [
                'ja' => '日本語',
                'th' => 'ไทย',
                'en' => 'English',
            ],
        ],
        'footer' => '© :year Mobile Order Mock',
    ],
    'navigation' => [
        'home' => 'Top Menu',
        'mobile-order' => 'Order Entry',
        'orders' => "Today's Orders",
        'products' => 'Product Master',
        'customers' => 'Customer Master',
        'settings' => 'Settings',
        'toggle_menu' => 'Toggle navigation',
    ],
    'index' => [
        'title' => 'Top Menu',
        'cards' => [
            'mobile-order' => [
                'title' => 'Order Entry',
                'description' => 'Quickly register orders from the field.',
            ],
            'orders' => [
                'title' => "Today's Orders",
                'description' => 'Check the progress of today’s orders at a glance.',
            ],
            'products' => [
                'title' => 'Product Master',
                'description' => 'Manage information for all handled products.',
            ],
            'customers' => [
                'title' => 'Customer Master',
                'description' => 'Keep track of client contacts and attributes.',
            ],
            'settings' => [
                'title' => 'Settings',
                'description' => 'Configure notification recipients and time zone.',
            ],
        ],
    ],
    'mobile_order' => [
        'title' => 'Order Entry',
        'fields' => [
            'order_date' => 'Order Date',
            'delivery_date' => 'Requested Delivery Date',
            'customer' => 'Customer',
            'product' => 'Product',
            'quantity' => 'Quantity',
            'notes' => 'Notes',
        ],
        'placeholders' => [
            'notes' => 'Enter additional notes if necessary',
        ],
        'buttons' => [
            'reset' => 'Clear',
            'submit' => 'Save Draft',
        ],
        'options' => [
            'customers' => [
                '鮮魚酒場 波しぶき',
                'レストラン 潮彩',
                'ホテル ブルーサンズ',
            ],
            'products' => [
                '本マグロ 柵 500g',
                'サーモン フィレ 1kg',
                'ボタンエビ 20尾',
                '真鯛 1尾',
            ],
        ],
    ],
    'orders' => [
        'title' => "Today's Orders",
        'table' => [
            'time' => 'Received At',
            'customer' => 'Customer',
            'items' => 'Order Details',
            'status' => 'Status',
        ],
        'statuses' => [
            'pending' => 'Pending Review',
            'preparing' => 'Preparing Shipment',
            'shipped' => 'Shipped',
        ],
        'samples' => [
            'tuna' => 'Bluefin tuna block 500g × 2',
            'salmon' => 'Salmon fillet 1kg × 5',
            'shrimp' => 'Botan shrimp 20 pieces × 3',
            'seabream' => 'Red seabream whole × 4',
        ],
    ],
    'products' => [
        'title' => 'Product Master',
        'table' => [
            'code' => 'Product Code',
            'name' => 'Product Name',
            'unit' => 'Unit',
            'price' => 'Unit Price (JPY)',
        ],
    ],
    'customers' => [
        'title' => 'Customer Master',
        'contact_label' => 'Contact',
        'contact_person' => 'Contact person',
        'notes_label' => 'Notes',
        'notes' => [
            'wave' => 'Deliver every morning at 8:00',
            'shiosai' => 'Prefers premium white fish',
            'blue_sands' => 'Places bulk orders regularly',
            'koharu' => 'Occasionally closed on Saturdays',
        ],
    ],
    'settings' => [
        'title' => 'Settings',
        'notifications' => [
            'title' => 'Notification Settings',
            'order_mail' => 'Order notification email',
            'alert_mail' => 'Emergency contact email',
            'slack' => 'Slack Webhook URL',
        ],
        'system' => [
            'title' => 'System Settings',
            'timezone' => 'Time zone',
            'open_time' => 'Business opening time',
            'close_time' => 'Business closing time',
        ],
        'buttons' => [
            'save' => 'Save',
            'draft' => 'Save Draft',
        ],
        'placeholders' => [
            'order_mail' => 'orders@example.com',
            'alert_mail' => 'alert@example.com',
            'slack' => 'https://hooks.slack.com/services/...',
        ],
    ],
    'auth' => [
        'login_title' => 'Login',
        'login_heading' => 'Sign in to Mobile Order',
        'login_subheading' => 'Use your registered email address and password to continue.',
        'email' => 'Email address',
        'password' => 'Password',
        'remember_me' => 'Keep me signed in',
        'login_button' => 'Log in',
        'logout_button' => 'Log out',
        'failed' => 'The provided credentials do not match our records.',
        'logged_in_as' => 'Logged in as :name',
    ],
];
