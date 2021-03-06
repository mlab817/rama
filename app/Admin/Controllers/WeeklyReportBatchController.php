<?php

namespace App\Admin\Controllers;

//use App\Jobs\GenerateWeeklyReport;
use App\Models\WeeklyReportBatch;
use Carbon\Carbon;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WeeklyReportBatchController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Weekly Report Batch';

    protected $description = [
        'index' => 'Click show on a report batch to view attached weekly reports'
    ];

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WeeklyReportBatch());

//        $grid->column('id', __('Id'));
        $grid->column('week_no', __('Week no'));
        $grid->column('start_date', __('Begin Date'));
        $grid->column('end_date', __('End Date'));
        $grid->column('user_id', __('Generated by'));
//        $grid->column('created_at', __('Created at'));
//        $grid->column('updated_at', __('Updated at'));
        $grid->column('updated_at', __('Last Updated'))
            ->display(function () {
                return $this->updated_at->diffForHumans(null, null, true) ?? '';
            })
            ->setAttributes(['class' => 'text-center']);

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
        $show = new Show(WeeklyReportBatch::findOrFail($id));

        $show->panel()
            ->tools(function ($tools) {
                $tools->disableEdit();
                $tools->disableDelete();
            });

        $show->field('week_no', __('Week No.'));
        $show->field('start_date', __('Begin Date'));
        $show->field('end_date', __('End Date'));

        $show->weekly_reports('Weekly Reports', function ($weekly_report) {
            $weekly_report->setResource('/auth/weekly-reports');

            $weekly_report->id();
            $weekly_report->operator()->name();
            $weekly_report->filepath('File')
                ->display(function () {
                    return $this->filepath
                        ? '<a target="_blank" href="' . Storage::disk('s3')->temporaryUrl($this->filepath, Carbon::now()->addMinutes(60), [
                            'ResponseContentType' => 'application/octet-stream',
                        ]) . '">Download</a>'
                        : '';
                });

//            $weekly_report->quickSearch('operator.name', 'Search operator...');
        });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new WeeklyReportBatch());

        $form->number('week_no', __('Week number'))->rules('required|unique:weekly_report_batches,week_no', [
            'unique' => 'We already have a set of reports for said week'
        ]);
        $form->date('start_date', __('Begin Date'))->rules('required|date');
        $form->date('end_date', __('End Date'))->rules('required|date|after_or_equal:start_date', [
            'after_or_equal' => 'Sorry, we do not do time travel here'
        ]);

        return $form;
    }
}
