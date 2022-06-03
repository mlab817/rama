<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PuvAttendance extends Model
{
    use HasFactory;

    protected $table = 'puv_attendance';

    protected $fillable = [
        'location',
    ];
}
