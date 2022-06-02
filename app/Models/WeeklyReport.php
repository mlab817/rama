<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'weekly_report_batch_id',
        'operator_id',
        'filepath'
    ];

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function weekly_report_batch()
    {
        return $this->belongsTo(WeeklyReportBatch::class);
    }
}
