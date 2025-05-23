<?php

if (!function_exists('getPermissionDescription')) {
    /**
     * Get the description for a specific permission
     *
     * @param string $permissionName The name of the permission
     * @return string The description of the permission
     */
    function getPermissionDescription(string $permissionName): string
    {
        $descriptions = [
            // User Management
            'manage_users' => 'Allows creating, editing, and deleting user accounts',
            'manage_roles' => 'Allows creating, editing, and deleting roles in the system',
            'manage_permissions' => 'Allows assigning and revoking permissions to roles and users',
            'show_users' => 'Allows viewing user account details',
            'edit_users' => 'Allows editing user account information',
            'delete_users' => 'Allows deleting user accounts',
            'admin_users' => 'Allows administrative actions on user accounts',
            
            // Order Management
            'manage_orders' => 'Allows viewing and managing all customer orders',
            'cancel_order' => 'Allows cancellation of orders and processing refunds',
            'place_order' => 'Allows placing new orders in the system',
            'view_orders' => 'Allows viewing order details',
            
            // Product Management
            'add_products' => 'Allows adding new products to the catalog',
            'edit_products' => 'Allows editing existing product information',
            'delete_products' => 'Allows removing products from the catalog',
            'list_products' => 'Allows viewing the product catalog',
            
            // Feedback Management
            'view_customer_feedback' => 'Allows viewing customer feedback and reports',
            'respond_to_feedback' => 'Allows responding to customer feedback',
            'view_feedback' => 'Allows viewing all feedback in the system',
            
            // Customer Management
            'list_customers' => 'Allows viewing customer information',
            'manage_employees' => 'Allows managing employee accounts and permissions',
        ];
        
        return $descriptions[$permissionName] ?? ucfirst(str_replace('_', ' ', $permissionName));
    }
}

if (!function_exists('hasAnyPermission')) {
    /**
     * Check if the user has any of the given permissions
     *
     * @param array|string $permissions The permissions to check
     * @return bool
     */
    function hasAnyPermission($permissions): bool
    {
        if (!auth()->check()) {
            return false;
        }
        
        return auth()->user()->hasAnyPermission($permissions);
    }
}
