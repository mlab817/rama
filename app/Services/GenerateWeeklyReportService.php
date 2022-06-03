<?php

namespace App\Services;

use App\Models\Operator;
use App\Models\WeeklyReport;
use App\Models\WeeklyReportBatch;
use App\Notifications\WeeklyReportGeneratedNotification;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GenerateWeeklyReportService
{
    public function execute(WeeklyReport $weeklyReport)
    {
        // $operator
        // $weekly_report_batch_id

        if ($weeklyReport->filepath) {
            // TODO: Need to notify user that the file already exists
            Log::info('File already exists. Skipping...');

            // terminate already
            // return;
        }

        // retrieve models
        $operator = Operator::find($weeklyReport->operator_id);

        $weeklyReportBatch = WeeklyReportBatch::find($weeklyReport->weekly_report_batch_id);

        try {
            // load vehicles and trips
            $operator->load([
                'vehicles.trips' => function ($query) use ($weeklyReportBatch) {
                    $query->where('start_date', ">=", $weeklyReportBatch->start_date)
                        ->where('end_date', "<=", $weeklyReportBatch->end_date);
                },
                'routes',
            ]);

            Log::info($operator->routes);

            $pdf = SnappyPdf::loadView('report', [
                'operator' => $operator,
                'week_no' => $weeklyReportBatch->week_no,
                'start_date' => $weeklyReportBatch->start_date,
                'end_date' => $weeklyReportBatch->end_date
            ]);

            // cleanup filename
            $filename = strtolower(preg_replace('/[^A-Za-z0-9\-\_]/', ' ', $operator->name));

            // generate file path
            $filepath = "reports/batch {$weeklyReportBatch->id}/{$operator->id} - {$filename}.pdf";

            // store file
            Storage::put($filepath, $pdf->output());

            // add filepath to weekly report
            $weeklyReport->filepath = $filepath;
            $weeklyReport->save();

            $weeklyReport->user->notify(new WeeklyReportGeneratedNotification($weeklyReport));

            // TODO: Notify user that report generation is completed
            Log::info('file generation completed');
        } catch (\Exception $exception) {
            $weeklyReport->user->notify(new WeeklyReportGeneratedNotification($weeklyReport));

            Log::error($exception->getMessage());
        }
    }
}
