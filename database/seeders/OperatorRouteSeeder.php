<?php

namespace Database\Seeders;

use App\Models\Operator;
use Encore\Admin\Auth\Database\Route;
use Encore\Admin\Auth\Database\VehicleInventory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperatorRouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $operatorRoutes = VehicleInventory::select('operator', 'route_code')
            ->distinct()
            ->get()
            ->map(function ($operator) {
                $operatorId = Operator::where('name', $operator->operator)->first()->id ?? null;
                $routeId = Route::where('code', $operator->route_code)->first()->id ?? null;

                if (! $operatorId || ! $routeId) {
                    return null;
                }

                return [
                    'operator_id' => $operatorId,
                    'route_id' => $routeId,
                ];
            })->reject(function ($item) {
                return empty($item);
            });

        DB::table('operator_route')->insert($operatorRoutes->toArray());
    }
}
