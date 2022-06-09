<?php

namespace App\Models;

use Encore\Admin\Auth\Database\Station;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Trip extends Model
{
    use HasFactory;
    use SoftDeletes;

    // TODO: add start_user_id, start_station_id, end_user_id, end_station_id
    protected $fillable = [
        'plate_no',
        'start_date',
        'start_time',
        'start_user_id',
        'start_station_id',
        'end_date',
        'end_time',
        'end_user_id',
        'end_station_id',
        'bound',
        'remarks',
        'user_id',
    ];

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Update model is_validated attribute to 1
     */
    public function mark_as_valid()
    {
        $this->is_validated = 1;
        $this->user_id = Auth::id();
        $this->save();
    }

    /**
     * Update model is_validated attribute to -1
     */
    public function mark_as_invalid()
    {
        $this->is_validated = -1;
        $this->user_id = Auth::id();
        $this->save();
    }
}
