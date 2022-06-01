<?php

namespace App\Jobs;

use App\Models\Operator;
use App\Models\WeeklyReport;
use App\Models\WeeklyReportBatch;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GenerateWeeklyReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $weeklyReportBatch;

    public $start_date;

    public $end_date;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($weeklyReportBatchId)
    {
        $weeklyReportBatch          = WeeklyReportBatch::find($weeklyReportBatchId);

        $this->weeklyReportBatch    = $weeklyReportBatch;
        $this->start_date           = $weeklyReportBatch->start_date;
        $this->end_date             = $weeklyReportBatch->end_date;

        Log::info('weekly report' . json_encode($weeklyReportBatch));
        Log::info('start date ' . $this->start_date);
        Log::info('end date' . $this->end_date);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $operators = Operator::all();

        foreach ($operators as $operator) {
            // TODO: Skip operators that already have a weekly report for this batch
            if ($this->checkIfExists($operator)) {
                echo $operator->operator_name . ' with weekly report batch ' . $this->weeklyReportBatch->id . ' already exists';

                continue;
            }

            $operator->load([
                'vehicles.trips' => function ($query) {
                    $query->where('start_date', ">=", $this->start_date)
                        ->where('end_date', "<=", $this->end_date);
                }
            ]);

            $this->generatePdfAndStore($this->weeklyReportBatch, $operator);
        }
    }

    public function generatePdfAndStore($weeklyReportBatch, $operator)
    {
        $pdf = SnappyPdf::loadView('report', [
            'operator' => $operator,
            'week_no' => $weeklyReportBatch->week_no,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date
        ]);

        $filename = strtolower(preg_replace('/[^A-Za-z0-9\-\_]/', '', $operator->operator_name));

        $filepath = "reports/batch {$weeklyReportBatch->id}/{$operator->id} - {$filename}.pdf";

        Storage::put($filepath, $pdf->output());

        $weeklyReport = WeeklyReport::create([
            'weekly_report_batch_id' => $weeklyReportBatch->id,
            'operator_id' => $operator->id,
            'filepath' => $filepath,
        ]);

        echo 'done with ' . $operator->operator_name . '\n';
    }

    /**
     * @param $operator
     *
     * @return boolean
     */
    public function checkIfExists($operator)
    {
        return WeeklyReport::where('weekly_report_batch_id', $this->weeklyReportBatch->id)
            ->where('operator_id', $operator->id)
            ->exists();
    }
}
