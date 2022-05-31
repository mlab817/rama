<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Auth\Permission;

class RegionController extends AdminController
{
    /**
     * {@inheritdoc}
     */
    protected function title()
    {
        return trans('admin.regions');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $regionModel = config('admin.database.regions_model');

        $grid = new Grid(new $regionModel());

        $grid->column('name', trans('admin.name'))->sortable();

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
        $regionModel = config('admin.database.regions_model');

        $show = new Show($regionModel::findOrFail($id));

        $show->panel()
        ->tools(function ($tools) {
            $tools->disableDelete();
        });

        $show->field('id', 'ID');
        $show->field('name', trans('admin.name'));
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
        $regionModel = config('admin.database.regions_model');

        $form = new Form(new $regionModel());

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
        });

        $form->text('name', trans('admin.name'))
            ->creationRules(['required', "unique:regions"])
            ->updateRules(['required', "unique:regions,name,{{id}}"]);

        return $form;
    }
}
