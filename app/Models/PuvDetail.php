<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PuvDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate_no',
        'date_scanned',
        'time_scanned',
        'station_id',
        'bound',
        'trip',
        'user_id',
    ];

    public const BOUNDS = [
        'NORTH',
        'SOUTH',
    ];

    public const TRIPS = [
        'START',
        'END',
        'IN-TRANSIT',
    ];
}
