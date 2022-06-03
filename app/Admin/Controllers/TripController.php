<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Trip\Invalidate;
use App\Admin\Actions\Trip\Validate;
use App\Models\Trip;
use Carbon\Carbon;
use Encore\Admin\Auth\Database\Station;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Log;

class TripController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Trips';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Trip());

        $grid->disableCreateButton();

        $grid->actions(function ($actions) {
            $actions->add(new Validate);
            $actions->add(new Invalidate);
        });

        $grid->column('plate_no', __('Plate no'))
            ->display(function () {
                return '<a href="/auth/trips?plate_no='. $this->plate_no .'">'. $this->plate_no.'</a>';
            });
        $grid->column('start_date', __('Start date'));
        $grid->column('start_time', __('Start time'));
        $grid->column('end_date', __('End date'));
        $grid->column('end_time', __('End time'));
        $grid->column('duration', __('Duration (mins)'))
            ->display(function () {
                return (int) Carbon::parse($this->end_time)->diffInMinutes($this->start_time);
            })
            ->setAttributes(['class' => 'text-center']);
        $grid->column('station.name', __('Station'));
        $grid->column('bound', __('Bound'));
//        $grid->column('created_at', __('Created at'));
//        $grid->column('updated_at', __('Updated at'));

        $grid->column('is_validated', __('Validated'))
            ->display(function () {
                return $this->is_validated == 1
                    ? '<i class="fa fa-check text-success"></i>'
                    : ($this->is_validated == -1 ? '<i class="fa fa-close text-danger"></i>' : '<i class="fa fa-square-o"></i>');
            })
            ->setAttributes(['class' => 'text-center']);

        $grid->column('user.username', __('By'));

        $grid->expandFilter();

        $vehiclesFromTrips = Trip::select('plate_no')->distinct()->orderBy('plate_no')->get()->pluck('plate_no','plate_no');

        $grid->filter(function ($filter) use ($vehiclesFromTrips) {
            $filter->disableIdFilter();

            $filter->date('start_date', 'Date');
            $filter->equal('plate_no', 'Plate Number')
                ->select($vehiclesFromTrips);
        });

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
        $show = new Show(Trip::findOrFail($id));

//        $show->field('id', __('Id'));
        $show->field('plate_no', __('Plate no'));
        $show->field('start_date', __('Start date'));
        $show->field('start_time', __('Start time'));
        $show->field('end_date', __('End date'));
        $show->field('end_time', __('End time'));
        $show->field('station.name', __('Station'));
        $show->field('bound', __('Bound'));
        $show->is_validated()->as(function ($val) {
            return $val == 1
                ? 'Validated'
                : ($val == -1 ? 'Invalid' : 'N/A');
            });
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Trip());

        $stations = Station::all()
            ->sortBy('name')
            ->pluck('name', 'id');

        $form->text('plate_no', __('Plate no'));
        $form->date('start_date', __('Start date'))->default(date('Y-m-d'));
        $form->time('start_time', __('Start time'))->default(date('H:i:s'));
        $form->date('end_date', __('End date'))->default(date('Y-m-d'));
        $form->time('end_time', __('End time'))->default(date('H:i:s'));
        $form->select('station_id', __('Station id'))
            ->options($stations);
        $form->select('bound', __('Bound'))
            ->options([
                'NORTH' => 'NORTH',
                'SOUTH' => 'SOUTH'
            ]);
        $form->radio('is_validated',__('Mark as Validated'))
            ->options([
                '1' => 'Valid',
                '-1' => 'Invalid',
            ])
            ->default('-1')
            ->stacked();
        $form->hidden('user_id')
            ->default(auth()->id());

        return $form;
    }
}
