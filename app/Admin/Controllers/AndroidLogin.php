<?php

namespace App\Admin\Controllers;

use Illuminate\Support\Facades\{Auth,DB};
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AndroidLogin extends Controller
{
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $updated = false;

        if ($request->has('username') and $request->has('password')){
            if (Auth::attempt(['username' => $request->username, 'password' => $request->password]))
            {
                $isActive = DB::table('users')->where('username', $request->username)->value('is_active');
                $data = DB::table('users')->where('username',$request->username)->first();

                if($isActive=="1"){
                    if ($data->updated_at !== null) {
                        $updated = true;
                    }

                    return response()->json([
                        'status'=> true,
                        'message' => "Login Successful!",
                        'data' => [
                            'userid' => $data->id,
                            'username' => $data->username,
                            'name' => $data->name,
                            'updated' => $updated,
                        ],
                    ], 200);
                } else {
                    return response()->json([
                        'status'=> false,
                        'message' => "Account Deactivated",
                    ], 200);
                }
            } else {
                return response()->json([
                    'status'=> false,
                    'message' => "Login Invalid",
                ], 200);
            }
         } else if ($request->has('scannedQRValue')){
            $isActive = DB::table('users')->where('qrcode', $request->scannedQRValue)->value('is_active');

            if($isActive=="1"){
                $data = DB::table('users')->where('qrcode',$request->scannedQRValue)->first();

                if ($data->updated_at !== null) {
                    $updated = true;
                }

                if ($data !== null) {
                    return response()->json([
                        'status'=> true,
                        'message' => "Login Successful!",
                        'data' => [
                            'userid' => $data->id,
                            'username' => $data->username,
                            'name' => $data->name,
                            'updated' => $updated,
                        ],
                    ], 200);
                } else {
                    return response()->json([
                        'status'=> false,
                        'message' => "Login Invalid",
                    ], 200);
                }
            } else {
                return response()->json([
                    'status'=> false,
                    'message' => "Account Deactivated",
                ], 200);
            }
         } else {
            return response()->json([
                'status'=> false,
                'message' => "Login Invalid",
            ], 200);
         }

    }
}
