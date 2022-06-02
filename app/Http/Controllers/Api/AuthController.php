<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // attempt login and retrieve token
        if (! $token = auth('api')->attempt($request->only('username','password'))) {
            return response()->json([
                'status'  => false,
                'message' => 'Incorrect username or password'
            ]);
        }

        $user = User::where('username', $request->username)->first();

        if (! $user->is_active) {
            return response()->json([
                'status'  => false,
                'message' => 'Account deactivated'
            ]);
        }

//        if ($request->has('username') and $request->has('password')){
//            if (Auth::attempt(['username' => $request->username, 'password' => $request->password]))
//            {
//                $isActive = DB::table('users')->where('username', $request->username)->value('is_active');
//                $data = DB::table('users')->where('username',$request->username)->first();
//            } else {
//
//            }
//        } else if ($request->has('scannedQRValue')){
//            $isActive = DB::table('users')->where('qrcode', $request->scannedQRValue)->value('is_active');
//
//            if($isActive=="1"){
//                $data = DB::table('users')->where('qrcode',$request->scannedQRValue)->first();
//
//                if ($data->updated_at !== null) {
//                    $updated = true;
//                }
//
//                if ($data !== null) {
//                    return response()->json([
//                        'status'=> true,
//                        'message' => "Login Successful!",
//                        'data' => [
//                            'userid' => $data->id,
//                            'username' => $data->username,
//                            'name' => $data->name,
//                            'updated' => $updated,
//                        ],
//                    ], 200);
//                } else {
//                    return response()->json([
//                        'status'=> false,
//                        'message' => "Login Invalid",
//                    ], 200);
//                }
//            } else {
//                return response()->json([
//                    'status'=> false,
//                    'message' => "Account Deactivated",
//                ], 200);
//            }
//        }

    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginViaQr(Request $request): JsonResponse
    {
        $qr = $request->scannedQRValue;

        $user = User::where('qrcode', $qr)->first();

        if (! $user || ! $token = auth('api')->login($user)) {
            return response()->json([
                'status' => false,
                'message'=> 'Invalid QR'
            ]);
        }

        if (! $user->is_active) {
            return response()->json([
                'status' => false,
                'message'=> 'Account deactivated'
            ]);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'status' => true,
            'data' => $user
        ]);
    }
}
