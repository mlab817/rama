<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Auth\Permission;

class StationController extends AdminController
{
    /**
     * {@inheritdoc}
     */
    protected function title()
    {
        return trans('admin.stations');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $stationModel = config('admin.database.stations_model');

        $grid = new Grid(new $stationModel());

        $grid->column('name', trans('admin.name'))->sortable();
        $grid->column('region.name', trans('admin.station'))->sortable();

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

        $grid->disableExport();

        $grid->filter(function (Grid\Filter $filter) {
            $filter->disableIdFilter();

            $filter->like('name', trans('admin.name'));
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
        $stationModel = config('admin.database.stations_model');

        $show = new Show($stationModel::findOrFail($id));

        $show->panel()
        ->tools(function ($tools) {
            $tools->disableDelete();
        });

        $show->field('id', 'ID');
        $show->field('name', trans('admin.name'));
        $show->field('region.name', trans('admin.station'));
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
        $stationModel = config('admin.database.stations_model');
        $regionModel = config('admin.database.regions_model');

        $form = new Form(new $stationModel());

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
        });

        $form->text('name', trans('admin.name'))
            ->creationRules(['required', "unique:stations"])
            ->updateRules(['required', "unique:stations,name,{{id}}"]);

        $form->select('region_id', trans('admin.region'))->options($regionModel::all()->pluck('name', 'id'))->rules('required');

        return $form;
    }
}
