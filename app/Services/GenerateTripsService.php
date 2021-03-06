<?php

namespace App\Services;

use App\Models\PuvDetail;
use App\Models\Trip;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class GenerateTripsService
{
    public function execute()
    {
        $previousTrip = null;

//        Schema::disableForeignKeyConstraints();

//        DB::table('trips')->truncate();

        // get the last date_time entry from the trips table
        $lastDateTimeEntry = Trip::orderBy('end_date')
            ->orderBy('end_time')
            ->latest()
            ->first();

        $puvDetails = PuvDetail::orderBy('plate_no')
            ->orderBy('date_scanned')
            ->orderBy('time_scanned')
            ->orderBy('bound')
//            ->where('date','>=',$lastDateTimeEntry->end_date)
//            ->where('time','>=', $lastDateTimeEntry->end_time)
            ->whereNull('trip_id') // get entries with no trip_id yet
            ->chunk(100, function ($data) {
                foreach ($data as $detail) {
                    // retrieve vehicle info to extract current route_code
                    $vehicle = Vehicle::where('plate_no', $detail->plate_no)->first();

                    if ($detail->trip == 'START') {
                        $previousTrip = Trip::create([
                            'plate_no' => $detail->plate_no,
                            'start_date' => $detail->date_scanned,
                            'start_time' => $detail->time_scanned,
                            'bound' => $detail->bound,
                            'station_id' => $detail->station_id,
                            'route_code' => $vehicle->route_code,
                        ]);
                    }

                    if ($detail->trip == 'END' && $previousTrip->plate_no == $detail->plate_no) {
                        $previousTrip->update([
                            'end_date' => $detail->date_scanned,
                            'end_time' => $detail->time_scanned,
                        ]);
                    }

                    $detail->trip_id = $previousTrip->id ?? null;
                    $detail->save();
                }
            });



//        Schema::disableForeignKeyConstraints();
    }
}
