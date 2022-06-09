<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PuvAttendance;
use App\Models\PuvDetail;
use App\Models\Trip;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ScanQRController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request): JsonResponse
    {
        // store raw data
        $puvAttendance = PuvAttendance::create([
            'name' => json_encode($request->all())
        ]);

        // validate required data
        $request->validate([
            'plate_no'      => 'required',
            'date_scanned'  => 'required|date',
            'time_scanned'  => 'required|date_format:H:i:s',
            'station_id'    => 'required|exists:stations,id',
            'bound'         => 'required|in:' . implode(',', PuvDetail::BOUNDS), // remember to change this if the values change
            'trip'          => 'required|in:' . implode(',', PuvDetail::TRIPS),
            'user_id'       => 'required:exists:users,id',
        ]);

        // validate that plate no exists
        if (! Vehicle::where('plate_no', $request->plate_no)->exists()) {
            // TODO: Notify admin that someone tried to scan an invalid QR
            return response()->json([
                'status'  => false,
                'message' => 'Plate no. ' . $request->plate_no . ' does not exist in the database.',
            ], 422); // invalid
        }

        $puvDetail = PuvDetail::create([
            'plate_no'      => $request->plate_no,
            'date_scanned'  => $request->date_scanned,
            'time_scanned'  => $request->time_scanned,
            'station_id'    => $request->station_id,
            'bound'         => $request->bound,
            'trip'          => $request->trip,
            'user_id'       => $request->user_id,
        ]);

        $this->createOrUpdateTrip($puvDetail);

        return response()->json([
            'data' => $puvDetail,
            'status' => true
        ], 200);
    }

    public function createOrUpdateTrip($puvDetail): void
    {
        $trip = null;

        // if the trip value is start, create a new trip record
        if ($puvDetail->trip == PuvDetail::TRIPS[0]) {
            $trip = Trip::create([
                'plate_no'              => $puvDetail->plate_no,
                'start_date'            => $puvDetail->date_scanned,
                'start_time'            => $puvDetail->time_scanned,
                'start_station_id'      => $puvDetail->station_id,
                'start_user_id'         => $puvDetail->user_id,
                'bound'                 => $puvDetail->bound,
            ]);
        }

        // if the trip is end
        if ($puvDetail->trip == PuvDetail::TRIPS[1]) {

            // find the latest trip with no end but with same date
            $trip = Trip::latest()
                ->where('plate_no', $puvDetail->plate_no)
                ->where('bound', $puvDetail->bound)
                ->where('start_date', $puvDetail->date_scanned)
                ->whereNull('end_date')
                ->first();

            // update relevant data
            if (! $trip) {
                Log::error('Missing START entry for: ' . route('admin.auth.list.show', $puvDetail));
            } else {
                $trip->update([
                    'end_date'          => $puvDetail->date_scanned,
                    'end_time'          => $puvDetail->time_scanned,
                    'end_station_id'    => $puvDetail->station_id,
                    'end_user_id'       => $puvDetail->user_id,
                ]);
            }
        }

        // log the trip
        if ($trip) {
            Log::info('Successfully created trip: '. route('trips.show', $trip));
        }
    }
}
