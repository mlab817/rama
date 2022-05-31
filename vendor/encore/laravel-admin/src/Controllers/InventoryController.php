<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Auth\Database\VehicleInventory;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Arr;
use Encore\Admin\Auth\Permission;

class InventoryController extends AdminController
{
    /**
     * {@inheritdoc}
     */
    protected function title()
    {
        return trans('admin.vehicle_inventory');
    }

    /**
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new VehicleInventory());

        $grid->model()->orderBy('id', 'DESC');

        $grid->column('consortium', trans('admin.consortium'))->sortable();
        $grid->column('operator', trans('admin.operator'))->sortable();
        $grid->column('plate_no', trans('admin.plate_no'))->sortable();
        $grid->column('fleet_no', trans('admin.fleet_no'))->sortable();
        $grid->column('type', trans('admin.type'));
        $grid->column('region.name', trans('admin.region'));
        $grid->column('route.name', trans('admin.route'));
        $grid->column('route_code', trans('admin.route_code'));
        $grid->column('remarks', trans('admin.remarks'));
        $grid->column('is_active', trans('admin.under_scp'))->using([true => 'Yes', false => 'No'])->display(function ($status) {
            $color = Arr::get(VehicleInventory::$statusColors, $status, 'yellow');

            return "<span class=\"badge bg-$color\">$status</span>";
        });
        $grid->column('is_onboarded', trans('admin.onboarded'))->using([true => 'Yes', false => 'No'])->display(function ($status) {
            $color = Arr::get(VehicleInventory::$statusColors, $status, 'yellow');

            return "<span class=\"badge bg-$color\">$status</span>";
        });

        if (!Permission::isAdministrator()) {
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableDelete();
            });

            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                    $actions->disableDelete();
                });
            });
        }

        $grid->filter(function (Grid\Filter $filter) {
            $regionModel = config('admin.database.regions_model');
            $routeModel = config('admin.database.routes_model');

            $filter->disableIdFilter();

            $filter->equal('consortium')->select(array_combine(VehicleInventory::$consortiumTypes, VehicleInventory::$consortiumTypes));
            $filter->like('operator', trans('admin.operator'));
            $filter->like('plate_no', trans('admin.plate_no'));
            $filter->like('fleet_no', trans('admin.fleet_no'));
            $filter->equal('type')->select(array_combine(VehicleInventory::$vehicleTypes, VehicleInventory::$vehicleTypes));
            $filter->equal('region_id',  trans('admin.region'))->select($regionModel::all()->pluck('name', 'id'));
            $filter->equal('route_code', trans('admin.route'))->select($routeModel::all()->pluck('name', 'code'));
            $filter->equal('remarks')->select(array_combine(VehicleInventory::$remarkTypes, VehicleInventory::$remarkTypes));

            $filter->in('is_active', trans('admin.under_scp'))->radio([
                true    => 'Yes',
                false   => 'No',
            ]);
            $filter->in('is_onboarded', trans('admin.onboarded'))->radio([
                true    => 'Yes',
                false   => 'No',
            ]);
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        $vehicleInventoryModel = config('admin.database.vehicle_inventory_model');

        $show = new Show($vehicleInventoryModel::findOrFail($id));

        $show->panel()
        ->tools(function ($tools) {
            $tools->disableDelete();
        });;

        $show->field('id', 'ID');
        $show->field('region.name', trans('admin.region'));
        $show->field('consortium', trans('admin.consortium'));
        $show->field('operator', trans('admin.operator'));
        $show->field('plate_no', trans('admin.plate_no'));
        $show->field('fleet_no', trans('admin.fleet_no'));
        $show->field('type', trans('admin.type'));
        $show->field('region.name', trans('admin.region'));
        $show->field('route.name', trans('admin.route'));
        $show->field('route_code', trans('admin.route_code'));
        $show->field('remarks', trans('admin.remarks'));
        $show->field('is_active', trans('admin.under_scp'))->using([true => 'Yes', false => 'No']);
        $show->field('is_onboarded', trans('admin.onboarded'))->using([true => 'Yes', false => 'No']);
        $show->field('created_at', trans('admin.created_at'));
        $show->field('updated_at', trans('admin.updated_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        $vehicleInventoryModel = config('admin.database.vehicle_inventory_model');
        $routeModel = config('admin.database.routes_model');
        $regionModel = config('admin.database.regions_model');

        $form = new Form(new $vehicleInventoryModel());

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
        });

        $vehicleInventoryTable = config('admin.database.vehicle_inventory_table');
        $connection = config('admin.database.connection');

        $form->select('consortium', trans('admin.consortium'))->options(array_combine(VehicleInventory::$consortiumTypes, VehicleInventory::$consortiumTypes))->rules('required');
        $form->text('operator', trans('admin.operator'))->rules('required');

        $form->text('plate_no', trans('admin.plate_no'))
            ->creationRules(['required', "unique:{$connection}.{$vehicleInventoryTable}"])
            ->updateRules(['required', "unique:{$connection}.{$vehicleInventoryTable},plate_no,{{id}}"]);

        $form->text('fleet_no', trans('admin.fleet_no'));
        $form->select('type', trans('admin.type'))->options(array_combine(VehicleInventory::$vehicleTypes, VehicleInventory::$vehicleTypes))->rules('required');
        $form->select('region_id', trans('admin.region'))->options($regionModel::all()->pluck('name', 'id'))->rules('required');
        $form->select('route_code', trans('admin.route'))->options($routeModel::all()->pluck('name', 'code'))->rules('required');
        $form->select('remarks', trans('admin.remarks'))->options(array_combine(VehicleInventory::$remarkTypes, VehicleInventory::$remarkTypes))->rules('required');
        $form->radio('is_active', trans('admin.under_scp'))->options([true => 'Yes', false => 'No'])->default(true);
        $form->radio('is_onboarded', trans('admin.onboarded'))->options([true => 'Yes', false => 'No'])->default(true);

        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = Hash::make($form->password);
            }
        });

        return $form;
    }
}
