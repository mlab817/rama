<?php

namespace App\Models;

use Encore\Admin\Auth\Database\Station;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate_no',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'bound',
        'station_id',
    ];

    public function station()
    {
        return $this->belongsTo(Station::class);
    }
}
