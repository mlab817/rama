<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Auth\Database\MasterList;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Arr;
use DB;
use Encore\Admin\Auth\Database\VehicleInventory;

class ListController extends AdminController
{
    /**
     * {@inheritdoc}
     */
    protected function title()
    {
        return trans('admin.masterlist');
    }

    /**
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new MasterList());

        $grid->model()->orderBy('id', 'DESC');

        $grid->column('date_scanned', trans('admin.date_scanned'))->sortable();
        $grid->column('time_scanned', trans('admin.time_scanned'))->sortable();
        $grid->column('plate_no', trans('admin.plate_no'))->sortable();
        $grid->column('vehicle.operator', trans('admin.operator'));
        $grid->column('vehicle.consortium', trans('admin.consortium'));
        $grid->column('vehicle.fleet_no', trans('admin.fleet_no'));
        $grid->column('vehicle.type', trans('admin.type'));

        $grid->column('vehicle.route_code', trans('admin.route'))->display(function($route_code) {
            return  DB::table(config('admin.database.routes_table'))->where('code', $route_code)->pluck('name')->first();
        });

        $grid->column('station.name', trans('admin.station'));
        $grid->column('bound', trans('admin.bound'))->using(['NB' => 'North', 'SB' => 'South']);
        $grid->column('user.name', trans('admin.scanned_by'));
        $grid->column('vehicle.remarks', trans('admin.remarks'));

        $grid->column('vehicle.is_active', trans('admin.under_scp'))->using([true => 'Yes', false => 'No'])->display(function ($status) {
            $color = Arr::get(VehicleInventory::$statusColors, $status, 'yellow');

            return "<span class=\"badge bg-$color\">$status</span>";
        });

        $grid->disableActions();

        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });

        $grid->disableCreateButton();

        $grid->filter(function (Grid\Filter $filter) {
            $routeModel = config('admin.database.routes_model');
            $stationModel = config('admin.database.stations_model');
            $userModel = config('admin.database.users_model');

            $filter->disableIdFilter();

            $filter->between('date_scanned', trans('admin.date_scanned'))->date();
            $filter->between('time_scanned', trans('admin.time_scanned'))->time();
            $filter->like('plate_no', trans('admin.plate_no'));
            $filter->like('vehicle.operator', trans('admin.operator'));
            $filter->equal('vehicle.consortium', trans('admin.consortium'))->select(array_combine(MasterList::$consortiumTypes, MasterList::$consortiumTypes));
            $filter->like('vehicle.fleet_no', trans('admin.fleet_no'));
            $filter->equal('vehicle.type', trans('admin.type'))->select(array_combine(MasterList::$vehicleTypes, MasterList::$vehicleTypes));
            $filter->equal('vehicle.route_code', trans('admin.route'))->select($routeModel::all()->pluck('name', 'code'));
            $filter->equal('station_id', trans('admin.station'))->select($stationModel::all()->pluck('name', 'id'));

            $filter->in('bound', trans('admin.bound'))->checkbox([
                'NB'    => 'North',
                'SB'    => 'South',
            ]);

            $filter->equal('user_id', trans('admin.scanned_by'))->select($userModel::all()->pluck('name', 'id'));
            $filter->equal('vehicle.remarks', trans('admin.remarks'))->select(array_combine(MasterList::$remarkTypes, MasterList::$remarkTypes));
        });

        return $grid;
    }
}
