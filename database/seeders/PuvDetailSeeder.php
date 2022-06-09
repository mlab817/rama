<?php

namespace Database\Seeders;

use App\Models\PuvDetail;
use App\Models\Vehicle;
use Carbon\Carbon;
use Encore\Admin\Auth\Database\VehicleInventory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker;
use Illuminate\Support\Facades\DB;

class PuvDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // bound
        // station
        // hour
        // day
        // trip = start, end
        DB::table('puv_details')->truncate();

        $vehicles = DB::table('vehicle_inventory')->where('route_code', 'E')->get()->pluck('plate_no');

        foreach ($vehicles as $vehicle) {
            $this->createOneMonthEntryForVehicle($vehicle);
        }
    }

    public function createOneMonthEntryForVehicle($vehicle)
    {
        $puvDetails = array();

        $startDate = Carbon::parse('2022-06-01');
        $currentDate = $startDate;
        $endDate = Carbon::parse('2022-06-30');

        $startBound = 'NORTH';
        $currentBound = $startBound;
        $startTrip = 'START';
        $currentTrip = $startTrip;

        $currentSid = 1;

        while ($currentDate <= $endDate) {
            $startHour = Carbon::parse('2022-06-01 04:00:00');
            $nextHour = $startHour;
            $endHour = Carbon::parse('2022-06-01 23:00:00');

            while ($nextHour <= $endHour) {
                $newData = [
                    'date_scanned' => $currentDate->format('Y-m-d'),
                    'time_scanned' => $nextHour->format('H:i:s'),
                    'plate_no'      => $vehicle,
                    'trip'          => $currentTrip,
                    'bound'         => $currentBound,
                    'station_id'    => $currentSid,
                    'user_id'       => 1
                ];

                $nextHour = $nextHour->addMinutes(45);

                if ($currentTrip == 'START') {
                    $currentTrip = 'END';
                    $currentSid = 18;
                } else {
                    $currentTrip = 'START';
                    $currentBound = $currentBound == 'NORTH' ? 'SOUTH' : 'NORTH';
                    $currentSid = $currentBound == 'NORTH' ? 18 : 1;
                }

                $puvDetails[] = $newData;
            }

            $currentDate = $currentDate->addDay();
        }

        DB::table('puv_details')->insert($puvDetails);

//        echo $vehicle;

        echo memory_get_peak_usage();
    }
}
