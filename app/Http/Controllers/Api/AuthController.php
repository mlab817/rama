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
    public function loginViaCredentials(Request $request): \Illuminate\Http\JsonResponse
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

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'status' => true,
            'data' => $user
        ]);
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
