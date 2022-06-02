<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->title('Dashboard')
//            ->description('...')
             ->row(function (Row $row) {

                $row->column(4, function (Column $column) {
//                    $column->append(Dashboard::environment());
                    $column->append(Dashboard::onboardedOperators());
                });


                $row->column(4, function (Column $column) {
//                    $column->append(Dashboard::environment());
                    $column->append(Dashboard::onboardedVehicles());
                });

                $row->column(4, function (Column $column) {
//                    $column->append(Dashboard::extensions());
                    $column->append(Dashboard::trips());
                });

            })->row(Dashboard::charts());
    }
}
