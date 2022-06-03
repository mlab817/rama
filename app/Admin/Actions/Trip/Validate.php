<?php

namespace App\Admin\Actions\Trip;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class Validate extends RowAction
{
    public $name = 'Validate';

    public function handle(Model $model)
    {
        $model->mark_as_valid();

        return $this->response()->success('Successfully marked trip as validated')->refresh();
    }

}
