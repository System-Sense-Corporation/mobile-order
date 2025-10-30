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
        'admin-users' => 'User Management',
        'settings' => 'Settings',
        'profile' => 'Profile',
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
    'admin' => [
        'users' => [
            'title' => 'User Management',
            'description' => 'Manage staff accounts and their permissions.',
            'create_button' => 'Create New',
            'create_title' => 'Create user',
            'create_description' => 'Register a new staff account and assign the appropriate role.',
            'validation_error_heading' => 'Please fix the highlighted fields.',
            'flash' => [
                'created' => 'User created successfully.',
                'permissions_updated' => 'Permissions updated successfully.',
            ],
            'roles' => [
                'admin' => 'Administrator',
                'manager' => 'Manager',
                'staff' => 'Staff',
            ],
            'role_descriptions' => [
                'admin' => 'Full access to every management screen.',
                'manager' => 'Can manage orders, products, and customers.',
                'staff' => 'Limited access for daily operations.',
            ],
            'form' => [
                'name' => [
                    'label' => 'Name',
                ],
                'email' => [
                    'label' => 'Email address',
                ],
                'phone' => [
                    'label' => 'Mobile phone',
                    'placeholder' => '080-1234-5678',
                ],
                'department' => [
                    'label' => 'Department',
                    'placeholder' => 'Select department',
                    'options' => [
                        'sales' => 'Sales',
                        'support' => 'Support',
                        'logistics' => 'Logistics',
                    ],
                ],
                'authority' => [
                    'label' => 'Role',
                ],
                'notify_new_orders' => [
                    'label' => 'Send new order notifications',
                    'help' => 'Receive an email when a new order is created.',
                ],
                'require_password_change' => [
                    'label' => 'Require password change on first login',
                    'help' => 'Force the user to change their password after signing in.',
                ],
                'password' => [
                    'label' => 'Password',
                    'placeholder' => 'At least 8 characters',
                ],
                'password_confirmation' => [
                    'label' => 'Confirm password',
                    'placeholder' => 'Re-enter password',
                ],
                'cancel_button' => 'Cancel',
                'submit_button' => 'Save user',
            ],
            'permissions_matrix' => [
                'title' => 'Permission matrix',
                'description' => 'Toggle the checkboxes to control which screens each role can access.',
                'table' => [
                    'permission' => 'Screen',
                    'description' => 'Description',
                ],
                'groups' => [
                    'general' => 'General navigation',
                    'orders' => 'Order operations',
                    'catalog' => 'Product management',
                    'customers' => 'Customer management',
                    'settings' => 'Configuration',
                ],
                'permissions' => [
                    'dashboard_view' => 'Dashboard',
                    'mobile_orders_view' => 'Order entry',
                    'orders_view' => 'Orders',
                    'products_view' => 'Product master',
                    'customers_view' => 'Customer master',
                    'settings_manage' => 'System settings',
                    'admin_users_manage' => 'User management',
                ],
                'descriptions' => [
                    'dashboard_view' => 'Access the top menu.',
                    'mobile_orders_view' => 'Create or edit mobile orders.',
                    'orders_view' => 'Review and track order progress.',
                    'products_view' => 'Browse the product catalog.',
                    'customers_view' => 'View customer information.',
                    'settings_manage' => 'Update notification and system settings.',
                    'admin_users_manage' => 'Manage user accounts and permissions.',
                ],
                'checkbox_label' => ':role can access :permission',
                'save_button' => 'Save changes',
            ],
            'errors' => [
                'invalid_role' => 'The selected role ":role" is invalid.',
                'invalid_permission' => 'The selected permission ":permission" is invalid.',
            ],
        ],
    ],
    'admin_users' => [
        'title' => 'User Management',
        'description' => 'Manage staff accounts and their permissions.',
        'actions' => [
            'create' => 'Create New',
        ],
        'filters' => [
            'permission' => 'Filter by Authority',
        ],
        'placeholders' => [
            'search' => 'Search by name, email, or department',
        ],
        'table' => [
            'no' => 'No.',
            'user_id' => 'User ID',
            'name' => 'Name',
            'department' => 'Department',
            'authority' => 'Authority',
            'email' => 'Email',
            'phone' => 'Mobile Phone',
            'status' => 'Status',
            'last_login' => 'Last Login',
        ],
        'authorities' => [
            'admin' => 'Administrator',
            'editor' => 'Editor',
            'viewer' => 'Viewer',
        ],
        'statuses' => [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'suspended' => 'Suspended',
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
    'profile' => [
        'title' => 'Profile',
        'description' => 'Manage how you sign in and what happens to your account.',
        'sections' => [
            'password' => [
                'title' => 'Update password',
                'description' => 'Set a strong password to keep your account secure.',
                'fields' => [
                    'current' => 'Current password',
                    'new' => 'New password',
                    'confirmation' => 'Confirm new password',
                ],
                'helper' => 'Use at least 12 characters that combine letters, numbers, and symbols.',
                'button' => 'Save new password',
            ],
            'account' => [
                'title' => 'Account management',
                'description' => 'Control how your account is handled when you no longer need it.',
                'delete_warning' => 'Deleting your account will remove all associated information. This action cannot be undone.',
                'support' => 'Need help deciding? Contact our support team before continuing.',
                'button' => 'Delete account',
            ],
        ],
    ],
];
