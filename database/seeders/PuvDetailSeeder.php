<?php

namespace Database\Seeders;

use App\Models\PuvDetail;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker;

class PuvDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // get 10 vehicles
        $vehicles = Vehicle::where('route_code', 'E')->select('plate_no')->take(10)->get();

        $startDate = Carbon::parse('2022-06-01');

        $endDate = Carbon::parse('2022-06-30');

        $nextDate = $startDate;

        // create per date
        while ($nextDate < $endDate) {

            foreach ($vehicles as $key => $vehicle) {

                $startTime = Carbon::parse('04:00:00');
                $endTime = Carbon::parse('23:59:59');

                $nextTime = $startTime;

                while ($nextTime < $endTime) {
                    echo $nextTime;

                    $randomMinutes = random_int(45, 75);

                    $bound = $key % 2 > 0 ? 'NORTH' : 'SOUTH';
                    $startStation = $bound == 'NORTH' ? 1 : 18;

                    PuvDetail::create([
                        'date_scanned' => $nextDate, //
                        'time_scanned' => $nextTime, //
                        'plate_no' => $vehicle->plate_no, //
                        'bound' => $bound, // NORTH, SOUTH
                        'station_id' => 1, //
                        'trip' => '', // START, END
                        'user_id' => '', //
                    ]);

                    $nextTime = $nextTime->addMinutes($randomMinutes);

                    PuvDetail::create([
                        'date_scanned' => $nextDate, //
                        'time_scanned' => $nextTime, //
                        'plate_no' => $vehicle->plate_no, //
                        'bound' => $bound, // NORTH, SOUTH
                        'station_id' => 1, //
                        'trip' => '', // START, END
                        'user_id' => '', //
                    ]);
                }

            }

            $nextDate = $nextDate->addDay();
            // add next minutes
        }

    }
}
