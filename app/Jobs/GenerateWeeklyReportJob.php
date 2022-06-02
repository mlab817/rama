<?php

namespace App\Jobs;

use App\Models\WeeklyReport;
use App\Services\GenerateWeeklyReportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateWeeklyReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public WeeklyReport $weeklyReport;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(WeeklyReport $weeklyReport)
    {
        $this->weeklyReport = $weeklyReport;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(GenerateWeeklyReportService $service)
    {
        $service->execute($this->weeklyReport);
    }
}
