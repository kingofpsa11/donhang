<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends \Spatie\Permission\Models\Permission
{
    public static function defaultPermissions()
    {
        return [
            'view_users',
            'add_users',
            'edit_users',
            'delete_users',

            'view_roles',
            'add_roles',
            'edit_roles',
            'delete_roles',

            'view_contracts',
            'add_contracts',
            'edit_contracts',
            'delete_contracts',

            'view_output_orders',
            'add_output_orders',
            'edit_output_orders',
            'delete_output_orders',

            'view_products',
            'add_products',
            'edit_products',
            'delete_products',

            'view_prices',
            'add_prices',
            'edit_prices',
            'delete_prices',

            'view_profit_rates',
            'add_profit_rates',
            'edit_profit_rates',
            'delete_profit_rates',

            'view_boms',
            'add_boms',
            'edit_boms',
            'delete_boms',

            'view_categories',
            'add_categories',
            'edit_categories',
            'delete_categories',

            'view_products',
            'add_products',
            'edit_products',
            'delete_products',

            'view_customers',
            'add_customers',
            'edit_customers',
            'delete_customers',

            'view_supplier',
            'add_supplier',
            'edit_supplier',
            'delete_supplier',

            'view_stores',
            'add_stores',
            'edit_stores',
            'delete_stores',

            'view_good_deliveries',
            'add_good_deliveries',
            'edit_good_deliveries',
            'delete_good_deliveries',

            'view_good_receives',
            'add_good_receives',
            'edit_good_receives',
            'delete_good_receives',
        ];
    }
}
