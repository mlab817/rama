<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends \Encore\Admin\Auth\Database\Region
{
    use HasFactory;

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'region_id');
    }
}
