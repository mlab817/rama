<?php

namespace App\Admin\Controllers;

use App\Jobs\GenerateWeeklyReportJob;
use App\Models\Operator;
use App\Models\WeeklyReport;
use App\Models\WeeklyReportBatch;
use App\Services\GenerateWeeklyReportService;
use Carbon\Carbon;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WeeklyReportController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Weekly Report';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WeeklyReport());

//        $grid->column('id', __('Id'));
        $grid->column('weekly_report_batch.week_no', __('Report batch'))
            ->display(function () {
                return 'Week No. ' . $this->weekly_report_batch->week_no;
            });
        $grid->column('operator.name', __('Operator'));
        $grid->column('filepath', __('Attachment'))
            ->display(function () {
                return $this->filepath
                    ? '<a target="_blank" href="' . Storage::disk('s3')->temporaryUrl($this->filepath, Carbon::now()->addMinutes(60), [
                        'ResponseContentType' => 'application/octet-stream',
                        'ResponseContentDisposition' => 'attachment; filename=filename.jpg',
                    ]) . '">Download</a>'
                    : '';
            });
        $grid->column('user.name', __('Generated by'));
//        $grid->column('created_at', __('Created at'));
//        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $weeklyReport = WeeklyReport::findOrFail($id);

        $show = new Show($weeklyReport);

        $show->setResource(route('report-batches.show', $weeklyReport->weekly_report_batch));

//        $show->field('id', __('Id'));
        $show->field('weekly_report_batch.week_no', __('Report batch'));
        $show->field('operator.name', __('Operator'));
        $show->field('filepath', __('Filepath'));
//        $show->field('created_at', __('Created at'));
//        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new WeeklyReport());

        $form->tools(function ($tools) {
            $tools->disableList();
        });

        $operators = Operator::select('id','name')
            ->get()
            ->pluck('name','id');

        $batches = WeeklyReportBatch::all()
            ->sortBy('week_no')
            ->map(function ($batch) {
                return [
                    'id' => $batch->id,
                    'label' => 'Week ' . $batch->week_no . ' (' . $batch->start_date . ' - ' . $batch->end_date . ')',
                ];
            })
            ->pluck('label', 'id');

        $form->select('weekly_report_batch_id', __('Report Batch'))
            ->options($batches)
            ->required()
            ->default(request()->query('weekly_report_batch_id'));

        $form->select('operator_id', __('Operator id'))
            ->options($operators)
            ->default(request()->query('operator_id'));

        $form->hidden('user_id')->default(auth()->id());

        $form->saved(function (Form $form) {
            GenerateWeeklyReportJob::dispatch($form->model(), auth()->id());

            return redirect()->route('report-batches.show', $form->model()->weekly_report_batch);
        });

        return $form;
    }
}
