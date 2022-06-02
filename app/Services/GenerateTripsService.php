<?php

namespace App\Services;

use App\Models\PuvDetail;
use App\Models\Trip;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class GenerateTripsService
{
    public function execute()
    {
        $puvDetails = PuvDetail::orderBy('plate_no')
            ->orderBy('date_scanned')
            ->orderBy('time_scanned')
            ->orderBy('bound')
            ->get();

        $previousTrip = null;

        Schema::disableForeignKeyConstraints();

        DB::table('trips')->truncate();

        foreach ($puvDetails as $detail) {
            if ($detail->trip == 'START') {
                $previousTrip = Trip::create([
                    'plate_no' => $detail->plate_no,
                    'start_date' => $detail->date_scanned,
                    'start_time' => $detail->time_scanned,
                    'bound' => $detail->bound,
                    'station_id' => $detail->station_id,
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

            Log::info($previousTrip);
        }

        Schema::disableForeignKeyConstraints();

        return Trip::all();
    }
}
