<?php

namespace App\Models;

use Encore\Admin\Auth\Database\VehicleInventory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends VehicleInventory
{
    use HasFactory;

    public function trips()
    {
        return $this->hasMany(Trip::class, 'plate_no', 'plate_no');
    }
}
