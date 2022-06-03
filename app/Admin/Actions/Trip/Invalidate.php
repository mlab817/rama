<?php

namespace App\Admin\Actions\Trip;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class Invalidate extends RowAction
{
    public $name = 'Invalidate';

    public function handle(Model $model)
    {
        $model->mark_as_invalid();

        return $this->response()->success('Successfully marked trip as invalid')->refresh();
    }

}
