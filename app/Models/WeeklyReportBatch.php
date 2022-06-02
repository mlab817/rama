<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyReportBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'week_no',
        'start_date',
        'end_date',
    ];

    public function weekly_reports()
    {
        return $this->hasMany(WeeklyReport::class);
    }
}
