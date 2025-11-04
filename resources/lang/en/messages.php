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
            'emailed' => 'Order report emailed successfully.',
            'email_failed' => 'Unable to send the report. Please check your mail settings and try again.',
        ],
        'actions' => [
            'edit' => 'Edit',
            'delete' => 'Delete',
            'download' => 'Download Excel',
            'send' => 'Send',
            'email_label' => 'Email address to send the report to',
            'email_placeholder' => 'recipient@example.com',
            'confirm_delete' => 'Delete this order? This action cannot be undone.',
        ],
        'mail' => [
            'subject' => "Today's orders export",
            'body' => 'The latest order report is attached as an Excel file.',
            'greeting' => 'Hello,',
            'signoff' => 'Thank you for using Mobile Order.',
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
        'actions' => [
            'create' => 'Add product',
            'edit' => 'Edit',
            'delete' => 'Delete',
            'confirm_delete' => 'Delete ":name" (:code)? This action cannot be undone.',
        ],
        'empty' => [
            'title' => 'No products registered yet',
            'description' => 'Start by adding a product using the button above.',
        ],
        'flash' => [
            'saved' => 'Product ":code" was saved successfully.',
            'updated' => 'Product ":code" was updated successfully.',
            'deleted' => 'Product ":code" was deleted.',
        ],
        'table' => [
            'code' => 'Product Code',
            'name' => 'Product Name',
            'unit' => 'Unit',
            'price' => 'Unit Price (JPY)',
            'actions' => 'Actions',
        ],
        'form' => [
            'edit_title' => 'Edit product',
            'title' => 'Product details',
            'description' => 'Register or edit product information to keep the master list up to date.',
            'validation_error' => 'Please check the highlighted fields and try again.',
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
                'update' => 'Update product',
            ],
            'sidebar' => [
                'title' => 'Product preview',
                'description' => 'Review the entered code, unit, and price before saving.',
                'note' => 'These preview values update based on the form inputs above.',
            ],
        ],
        'validation' => [
            'code' => [
                'required' => 'Please enter a product code.',
                'unique' => 'This product code is already in use.',
            ],
            'name' => [
                'required' => 'Please enter a product name.',
            ],
            'price' => [
                'required' => 'Please enter a price.',
                'integer' => 'Price must be a whole number.',
                'min' => 'Price cannot be negative.',
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
            'edit' => 'Edit',
            'delete' => 'Delete',
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
            'actions' => 'Actions',
        ],
        'authorities' => [
            'admin' => 'Administrator',
            'editor' => 'Editor',
            'viewer' => 'Viewer',
        ],
        'roles' => [
            'admin' => 'Administrator',
            'editor' => 'Editor',
            'viewer' => 'Viewer',
        ],
        'role_descriptions' => [
            'admin' => 'Full access to manage system settings and users.',
            'editor' => 'Can manage orders, products, and customers.',
            'viewer' => 'Can browse data but cannot make changes.',
        ],
        'statuses' => [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'suspended' => 'Suspended',
        ],
        'create' => [
            'title' => 'Create User',
            'description' => 'Register a new staff account and assign the appropriate authority.',
        ],
        'edit' => [
            'title' => 'Edit User',
            'description' => 'Update the staff member’s details or permissions.',
        ],
        'form' => [
            'validation_error_heading' => 'Please fix the issues below and try again.',
            'name' => [
                'label' => 'Full name',
                'placeholder' => 'e.g. John Smith',
            ],
            'email' => [
                'label' => 'Email address',
                'placeholder' => 'user@example.com',
            ],
            'phone' => [
                'label' => 'Mobile phone',
                'placeholder' => 'e.g. 090-1234-5678',
            ],
            'department' => [
                'label' => 'Department',
                'placeholder' => 'Select a department',
                'options' => [
                    'sales' => 'Sales',
                    'support' => 'Customer Support',
                    'logistics' => 'Logistics',
                    'management' => 'Management',
                ],
            ],
            'authority' => [
                'label' => 'Authority level',
            ],
            'notify_new_orders' => [
                'label' => 'Notify about new orders',
                'help' => 'Send email notifications whenever a new order is submitted.',
            ],
            'require_password_change' => [
                'label' => 'Require password change on first login',
                'help' => 'Prompt the user to set a new password after their first sign-in.',
            ],
            'password' => [
                'label' => 'Temporary password',
                'placeholder' => 'Enter a temporary password',
            ],
            'password_confirmation' => [
                'label' => 'Confirm password',
                'placeholder' => 'Re-enter the password',
            ],
            'cancel_button' => 'Cancel',
            'submit_button' => 'Create user',
            'submit_button_update' => 'Save changes',
        ],
        'flash' => [
            'created' => 'The user has been registered successfully.',
            'updated' => 'The user has been updated successfully.',
            'deleted' => 'The user has been removed.',
        ],
        'confirm_delete' => 'Are you sure you want to delete :name?',
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
            'account_information' => [
                'title' => 'Account information',
                'description' => 'Update your display name, department, and contact details.',
                'fields' => [
                    'name' => 'Full name',
                    'email' => 'Email address',
                    'department' => 'Department',
                    'department_placeholder' => 'Select your department',
                    'telephone' => 'Mobile phone number',
                ],
                'button' => 'Save changes',
            ],
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
        'flash' => [
            'profile_updated' => 'Profile information updated successfully.',
            'password_updated' => 'Password updated successfully.',
        ],
    ],
];
