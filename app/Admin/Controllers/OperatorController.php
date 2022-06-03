<?php

namespace App\Admin\Controllers;

use App\Models\Operator;
use App\Models\Vehicle;
use Encore\Admin\Auth\Database\Region;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Storage;

class OperatorController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Operator';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Operator());

//        $grid->column('id', __('Id'));
        $grid->column('region.name', __('Region'))->sortable();
        $grid->column('name', __('Operator'))->sortable();
        $grid->column('contact_number', __('Contact No.'));
        $grid->column('email', __('Email Address'));
        $grid->column('full_address', __('Full Address'));
//        $grid->column('created_at', __('Created at'));
//        $grid->column('updated_at', __('Updated at'));
        $grid->column('vehicles', __('No. of vehicles'))
            ->display(function ($vehicles) {
                return count($vehicles);
            });

        $grid->quickSearch('name')
            ->placeholder('Operator');

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
        $show = new Show(Operator::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('region.name', __('Region'));
        $show->field('name', __('Operator'));

        $show->vehicles('Vehicles', function ($vehicle) {
            $vehicle->setResource('/auth/inventory');

            $vehicle->id();
            $vehicle->region()->name();
            $vehicle->plate_no('Plate Number');
            $vehicle->type('Vehicle Mode');
            $vehicle->route_code('Route Code');

            $vehicle->quickSearch('plate_no')
                ->placeholder('Search plate nos...');
        });

        $show->weekly_reports('Weekly Reports', function ($report) {
            $report->setResource('/auth/weekly-reports');

            $report->weekly_report_batch()->week_no('Batch');
            $report->weekly_report_batch()->start_date('Start Date');
            $report->weekly_report_batch()->end_date('End Date');
            $report->filepath()->link(function ($val) {
                return Storage::url($val->filepath);
            });
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
        $form = new Form(new Operator());

        $form->select('region_id', __('Region'))
            ->options(Region::all()->map(function ($region) {
                return [$region->id => $region->name];
            })->collapse());
        $form->text('name', __('Operator'));
        $form->text('contact_number', __('Contact number'));
        $form->text('email', __('Email address'));
        $form->text('full_address', __('Full Address'));

        return $form;
    }
}
