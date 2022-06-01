<?php

namespace App\Models;

use Encore\Admin\Auth\Database\Region;
use Encore\Admin\Auth\Database\VehicleInventory;
use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    use HasFactory;

    use ModelTree;
    use AdminBuilder;

    protected $fillable = [
        'name',
        'region_id',
        'contact_number',
        'email',
        'full_address',
    ];

    public function vehicles()
    {
        return $this->hasMany(VehicleInventory::class, 'operator', 'name');
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
