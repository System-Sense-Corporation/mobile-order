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
            'customer' => 'Select a customer',
            'product' => 'Select a product',
            'notes' => 'Enter additional notes if necessary',
        ],
        'buttons' => [
            'reset' => 'Clear',
            'submit' => 'Save Order',
            'update' => 'Update Order',
        ],
        'empty' => [
            'customers' => 'No customers available',
            'products' => 'No products available',
        ],
        'flash' => [
            'saved' => 'The order was registered successfully.',
            'updated' => 'The order was updated successfully.',
        ],
        'demo_notice' => 'Sample customer and product data are shown for preview only.',
    ],
    'orders' => [
        'title' => "Today's Orders",
        'table' => [
            'time' => 'Received At',
            'customer' => 'Customer',
            'items' => 'Order Details',
            'status' => 'Status',
            'actions' => 'Actions',
        ],
        'labels' => [
            'delivery' => 'Delivery',
            'notes' => 'Notes',
        ],
        'statuses' => [
            'pending' => 'Pending Review',
            'preparing' => 'Preparing Shipment',
            'shipped' => 'Shipped',
        ],
        'flash' => [
            'status_updated' => 'Order status updated.',
            'updated' => 'Order details updated.',
            'deleted' => 'Order deleted.',
        ],
        'actions' => [
            'edit' => 'Edit',
            'delete' => 'Delete',
            'confirm_delete' => 'Delete this order? This action cannot be undone.',
        ],
        'empty' => 'No orders have been submitted yet.',
        'samples' => [
            'tuna' => 'Bluefin tuna block 500g × 2',
            'salmon' => 'Salmon fillet 1kg × 5',
            'shrimp' => 'Botan shrimp 20 pieces × 3',
            'seabream' => 'Red seabream whole × 4',
        ],
    ],
    'products' => [
        'title' => 'Product Master',
        'description' => 'Reference standard units and pricing for handled seafood items.',
        'demo_notice' => 'Sample product data is shown for preview only.',
        'actions' => [
            'create' => 'Add product',
        ],
        'table' => [
            'code' => 'Product Code',
            'name' => 'Product Name',
            'unit' => 'Unit',
            'price' => 'Unit Price (JPY)',
        ],
        'form' => [
            'title' => 'Product details',
            'description' => 'Register or edit product information to keep the master list up to date.',
            'fields' => [
                'code' => 'Product code',
                'name' => 'Product name',
                'unit' => 'Unit',
                'price' => 'Unit price (JPY)',
            ],
            'placeholders' => [
                'code' => 'e.g. P-1001',
                'name' => 'Enter product name',
                'unit' => 'Enter unit (pack, case, etc.)',
                'price' => 'Enter price per unit',
            ],
            'buttons' => [
                'cancel' => 'Back to list',
                'save' => 'Save product',
            ],
            'sidebar' => [
                'title' => 'Sample master data',
                'description' => 'Use the form to keep product codes, units, and prices aligned with the reference list.',
                'note' => 'These demo values mirror what is displayed on the product master table.',
            ],
        ],
    ],
    'customers' => [
        'title' => 'Customer Master',
        'description' => 'Manage client contact information and internal notes.',
        'contact_label' => 'Contact',
        'contact_person' => 'Contact person',
        'notes_label' => 'Notes',
        'notes' => [
            'wave' => 'Deliver every morning at 8:00',
            'shiosai' => 'Prefers premium white fish',
            'blue_sands' => 'Places bulk orders regularly',
        ],
        'demo_notice' => 'Sample customer data is shown for preview only.',
        'actions' => [
            'create' => 'Add customer',
            'edit' => 'Edit',
            'delete' => 'Delete',
            'update' => 'Save changes',
            'confirm_delete' => 'Are you sure you want to delete ":name"? This action cannot be undone.',
        ],
        'empty' => [
            'title' => 'No customers registered yet',
            'description' => 'Start by adding a customer using the button above.',
        ],
        'flash' => [
            'saved' => 'Customer ":name" was saved successfully.',
            'updated' => 'Customer ":name" was updated successfully.',
            'deleted' => 'Customer ":name" was deleted successfully.',
        ],
        'form' => [
            'title' => 'Customer details',
            'description' => 'Review and update registration details for this customer.',
            'status' => [
                'editing' => 'Editing draft',
                'creating' => 'New customer',
            ],
            'fields' => [
                'name' => 'Customer name',
                'contact' => 'Contact information',
                'person' => 'Primary contact person',
                'note' => 'Internal note',
            ],
            'placeholders' => [
                'name' => 'Enter customer name',
                'contact' => 'Enter phone number or email',
                'person' => 'Enter contact person',
                'note' => 'Add reminders for deliveries, preferences, etc.',
            ],
            'buttons' => [
                'cancel' => 'Cancel',
                'save' => 'Save customer',
                'update' => 'Update customer',
            ],
            'sidebar' => [
                'title' => 'Registration summary',
                'labels' => [
                    'customer_code' => 'Customer code',
                    'created_at' => 'Created on',
                    'last_updated' => 'Last updated',
                ],
                'note' => 'Use this form to keep customer contact details consistent with the master list.',
            ],
        ],
        'validation' => [
            'name' => [
                'required' => 'Please enter a customer name.',
                'string' => 'Customer name must be text.',
                'max' => 'Customer name may not be greater than :max characters.',
            ],
            'contact' => [
                'string' => 'Contact information must be text.',
                'max' => 'Contact information may not be greater than :max characters.',
            ],
            'contact_person' => [
                'string' => 'Contact person must be text.',
                'max' => 'Contact person may not be greater than :max characters.',
            ],
            'notes' => [
                'string' => 'Notes must be text.',
                'max' => 'Notes may not be greater than :max characters.',
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
