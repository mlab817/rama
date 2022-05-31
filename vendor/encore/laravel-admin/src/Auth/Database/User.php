<?php

namespace Encore\Admin\Auth\Database;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public static $roleColors = [
        'Administrator' => 'grey',
        'User'          => 'blue',
    ];

    public static $statusColors = [
        'Active'        => 'green',
        'Deactivated'   => 'red',
    ];
}
