<?php
   
if (!function_exists('isPrime')) {
    function isPrime($number)
    {
        if($number<=1) return false;
        $i = $number - 1;
        while($i>1) {
        if($number%$i==0) return false;
        $i--;
        }
        return true;
    }
}

/**
 * Helper functions for the application
 */

/**
 * Get a human-readable description for a permission
 */
function getPermissionDescription(string $permissionName): string
{
    $descriptions = [
        // Admin permissions
        'manage_users' => 'Create, edit, and delete user accounts',
        'manage_roles' => 'Create and assign user roles with specific permissions',
        'manage_permissions' => 'Control what actions users can perform',
        'view_logs' => 'View system logs and activity history',
        'access_admin_panel' => 'Access the administrative dashboard',
        
        // Employee permissions
        'view_products' => 'View product details and inventory',
        'manage_orders' => 'Process customer orders, add credits, and handle payments',
        'view_customers' => 'View customer information and order history',
        'manage_feedback' => 'Handle customer feedback and inquiries',
        
        // Feedback permissions
        'view_customer_feedback' => 'See customer feedback and cancellation reasons',
        'respond_to_feedback' => 'Reply to customer feedback and resolve issues',
        'view_order_cancellations' => 'See orders that have been cancelled',
        'receive_cancellation_notifications' => 'Get notified when orders are cancelled',
        'manage_notifications' => 'Control notification settings',
        
        // Order permissions
        'cancel_order' => 'Cancel pending or processing orders',
        
        // Product permissions
        'add_products' => 'Add new products to the catalog',
        'edit_products' => 'Modify existing product information',
        'delete_products' => 'Remove products from the catalog',
    ];
    
    return $descriptions[$permissionName] ?? 'No description available';
}
