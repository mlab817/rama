<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PuvAttendance;
use App\Models\PuvDetail;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class ScanQRController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'qrcode'        => 'required',
            'date_scanned'  => 'required|date',
            'time_scanned'  => 'required|date_format:H:i:s',
            'station_id'    => 'required|exists:stations,id',
            'bound'         => 'required|in:' . implode(',', PuvDetail::BOUNDS), // remember to change this if the values change
        ]);

        // create entry in PUV attendance
        PuvAttendance::create(['location' => $request->qrcode]);

        $array = explode('/', $request->qrcode);

        // assumes that the last part of the string is plate no
        // this is to avoid cases for operators that have "/"
        // in their names
        $plate_no = count($array) > 0 ? $array[count($array)-1] : null;

        // validate that plate no exists
        if (! Vehicle::where('plate_no', $plate_no)->exists()) {
            // TODO: Notify admin that someone tried to scan an invalid QR
            return response()->json([
                'status'  => false,
                'message' => 'Plate no. ' . $plate_no . ' does not exist in the database.',
            ], 422); // invalid
        }

        $puvDetail = PuvDetail::create([
//            'qrcode' => $request->qrcode,
            'plate_no'      => $plate_no,
            'date_scanned'  => $request->date_scanned,
            'time_scanned'  => $request->time_scanned,
            'station_id'    => $request->station_id,
            'bound'         => $request->bound,
            'user_id'       => auth('api')->id()
        ]);

        return response()->json([
            'data' => $puvDetail,
            'status' => true
        ], 200);
    }
}
