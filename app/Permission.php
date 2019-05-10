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
        ];
    }
}
