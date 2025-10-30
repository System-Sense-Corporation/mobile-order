<?php

return [
    'groups' => [
        'general' => 'messages.admin.users.permissions_matrix.groups.general',
        'orders' => 'messages.admin.users.permissions_matrix.groups.orders',
        'catalog' => 'messages.admin.users.permissions_matrix.groups.catalog',
        'customers' => 'messages.admin.users.permissions_matrix.groups.customers',
        'settings' => 'messages.admin.users.permissions_matrix.groups.settings',
    ],
    'permissions' => [
        'dashboard.view' => [
            'group' => 'general',
            'route' => 'home',
            'label' => 'messages.admin.users.permissions_matrix.permissions.dashboard_view',
            'description' => 'messages.admin.users.permissions_matrix.descriptions.dashboard_view',
        ],
        'mobile_orders.view' => [
            'group' => 'orders',
            'route' => 'mobile-order',
            'label' => 'messages.admin.users.permissions_matrix.permissions.mobile_orders_view',
            'description' => 'messages.admin.users.permissions_matrix.descriptions.mobile_orders_view',
        ],
        'orders.view' => [
            'group' => 'orders',
            'route' => 'orders',
            'label' => 'messages.admin.users.permissions_matrix.permissions.orders_view',
            'description' => 'messages.admin.users.permissions_matrix.descriptions.orders_view',
        ],
        'products.view' => [
            'group' => 'catalog',
            'route' => 'products',
            'label' => 'messages.admin.users.permissions_matrix.permissions.products_view',
            'description' => 'messages.admin.users.permissions_matrix.descriptions.products_view',
        ],
        'customers.view' => [
            'group' => 'customers',
            'route' => 'customers',
            'label' => 'messages.admin.users.permissions_matrix.permissions.customers_view',
            'description' => 'messages.admin.users.permissions_matrix.descriptions.customers_view',
        ],
        'settings.manage' => [
            'group' => 'settings',
            'route' => 'settings',
            'label' => 'messages.admin.users.permissions_matrix.permissions.settings_manage',
            'description' => 'messages.admin.users.permissions_matrix.descriptions.settings_manage',
        ],
        'admin.users.manage' => [
            'group' => 'settings',
            'route' => 'admin.users.index',
            'label' => 'messages.admin.users.permissions_matrix.permissions.admin_users_manage',
            'description' => 'messages.admin.users.permissions_matrix.descriptions.admin_users_manage',
        ],
    ],
    'roles' => [
        'admin' => [
            'label' => 'messages.admin.users.roles.admin',
            'description' => 'messages.admin.users.role_descriptions.admin',
            'permissions' => [
                'dashboard.view',
                'mobile_orders.view',
                'orders.view',
                'products.view',
                'customers.view',
                'settings.manage',
                'admin.users.manage',
            ],
        ],
        'manager' => [
            'label' => 'messages.admin.users.roles.manager',
            'description' => 'messages.admin.users.role_descriptions.manager',
            'permissions' => [
                'dashboard.view',
                'mobile_orders.view',
                'orders.view',
                'products.view',
                'customers.view',
            ],
        ],
        'staff' => [
            'label' => 'messages.admin.users.roles.staff',
            'description' => 'messages.admin.users.role_descriptions.staff',
            'permissions' => [
                'dashboard.view',
                'mobile_orders.view',
                'orders.view',
            ],
        ],
    ],
];
