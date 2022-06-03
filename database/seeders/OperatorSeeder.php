<?php

namespace Database\Seeders;

use App\Models\Operator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Encore\Admin\Auth\Database\VehicleInventory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OperatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('operators')->truncate();

        $uniqueOperators = VehicleInventory::select('operator','region_id')->distinct()->get();

        foreach ($uniqueOperators as $operator) {
            Operator::create([
                'name'          => $operator->operator,
                'region_id'     => $operator->region_id,
            ]);
            echo 'created: ' . $operator->operator;
        }

        Schema::enableForeignKeyConstraints();
    }
}
