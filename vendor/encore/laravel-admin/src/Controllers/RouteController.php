<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Auth\Permission;

class RouteController extends AdminController
{
    /**
     * {@inheritdoc}
     */
    protected function title()
    {
        return trans('admin.routes');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $routeModel = config('admin.database.routes_model');

        $grid = new Grid(new $routeModel());

        $grid->column('name', trans('admin.name'))->sortable();
        $grid->column('code', trans('admin.code'))->sortable();

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
            $filter->disableIdFilter();

            $filter->like('name', trans('admin.name'));
            $filter->like('code', trans('admin.code'));
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
        $routeModel = config('admin.database.routes_model');

        $show = new Show($routeModel::findOrFail($id));

        $show->panel()
        ->tools(function ($tools) {
            $tools->disableDelete();
        });

        $show->field('id', 'ID');
        $show->field('name', trans('admin.name'));
        $show->field('code', trans('admin.code'));
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
        $routeModel = config('admin.database.routes_model');

        $form = new Form(new $routeModel());

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
        });

        $routeTable = config('admin.database.routes_table');
        $connection = config('admin.database.connection');

        $form->text('name', trans('admin.name'))
        ->creationRules(['required', "unique:{$connection}.{$routeTable}"])
        ->updateRules(['required', "unique:{$connection}.{$routeTable},name,{{id}}"]);

        $form->text('code', trans('admin.code'))
        ->creationRules(['required', "unique:{$connection}.{$routeTable}"])
        ->updateRules(['required', "unique:{$connection}.{$routeTable},code,{{id}}"]);

        return $form;
    }
}
