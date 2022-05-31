<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Auth\Database\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Encore\Admin\Auth\Permission;

class UserController extends AdminController
{
    /**
     * {@inheritdoc}
     */
    protected function title()
    {
        return trans('admin.users');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $userModel = config('admin.database.users_model');

        $grid = new Grid(new $userModel());

        $grid->model()->orderBy('id', 'DESC');

        $grid->column('name', trans('admin.name'))->sortable();
        $grid->column('username', trans('admin.username'))->sortable();
        $grid->column('position', trans('admin.position'));
        $grid->column('region.name', trans('admin.region'));
        $grid->column('qrcode', trans('admin.qrcode'))->qrcode()->sortable();
        $grid->column('roles', trans('admin.roles'))->pluck('name')->first()->display(function ($role) {
            $color = Arr::get(User::$roleColors, $role, 'yellow');

            if ($color !== "grey" && $color !== "blue") {
                $color = 'yellow';
            }

            return "<span class=\"badge bg-$color\">$role</span>";
        });
        $grid->column('is_active', trans('admin.account_status'))->using([true => 'Active', false => 'Deactivated'])->display(function ($status) {
            $color = Arr::get(User::$statusColors, $status, 'yellow');

            return "<span class=\"badge bg-$color\">$status</span>";
        });

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableDelete();
        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });

        $grid->filter(function (Grid\Filter $filter) {
            $regionModel = config('admin.database.regions_model');

            $filter->disableIdFilter();

            $filter->like('name', trans('admin.name'));
            $filter->like('username', trans('admin.username'));
            $filter->like('position', trans('admin.position'));
            $filter->equal('region_id', 'Region')->select($regionModel::all()->pluck('name', 'id'));

            $filter->in('is_active', trans('admin.account_status'))->radio([
                true    => 'Active',
                false   => 'Deactivated',
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
        $userModel = config('admin.database.users_model');

        $show = new Show($userModel::findOrFail($id));

        $show->panel()
        ->tools(function ($tools) {
            $tools->disableDelete();
        });

        $show->field('id', 'ID');
        $show->field('name', trans('admin.name'));
        $show->field('position', trans('admin.position'));
        $show->field('region.name', trans('admin.region'));
        $show->field('email', trans('admin.email'));
        $show->field('qrcode', trans('admin.qrcode'));
        $show->field('username', trans('admin.username'));
        $show->field('roles', trans('admin.roles'))->as(function ($roles) {
            return $roles->pluck('name')->first();
        });
        $show->field('permissions', trans('admin.permissions'))->as(function ($permission) {
            return $permission->pluck('name');
        })->label();
        $show->field('is_active', trans('admin.account_status'))->using([true => 'Active', false => 'Deactivated']);
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
        $userModel = config('admin.database.users_model');
        $permissionModel = config('admin.database.permissions_model');
        $roleModel = config('admin.database.roles_model');
        $regionModel = config('admin.database.regions_model');

        $form = new Form(new $userModel());
        $id = NULL;

        if ($form->isEditing()) { 
            $id = request()->route()->parameter('user'); $model = $form->model()->find($id); 
        }

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
        });

        $userTable = config('admin.database.users_table');
        $connection = config('admin.database.connection');

        $form->text('name', trans('admin.name'))->rules('required');
        $form->text('position', trans('admin.position'));
        $form->select('region_id', trans('admin.region'))->options($regionModel::all()->pluck('name', 'id'))->rules('required');
        $form->email('email', trans('admin.email'));
        $form->text('qrcode', trans('admin.qrcode'));

        if ($id != 1) {
            $form->text('username', trans('admin.username'))
                ->creationRules(['required', 'min:4', 'max:25', "unique:{$connection}.{$userTable}"])
                ->updateRules(['required', 'min:4', 'max:25', "unique:{$connection}.{$userTable},username,{{id}}"]);
            $form->password('password', trans('admin.password'))->rules('required|confirmed|min:4');
            $form->password('password_confirmation', trans('admin.password_confirmation'))->rules('required|min:4')
                ->default(function ($form) {
                    return $form->model()->password;
                });

            $form->ignore(['password_confirmation']);

            $form->multipleSelect('roles', trans('admin.roles'))->options($roleModel::all()->except(1)->pluck('name', 'id'));
            $form->multipleSelect('permissions', trans('admin.permissions'))->options($permissionModel::all()->pluck('name', 'id'));
            $form->radio('is_active', trans('admin.account_status'))->options([true => 'Active', false => 'Deactivated'])->default(true);
        }

        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = Hash::make($form->password);
            }
        });

        return $form;
    }
}
