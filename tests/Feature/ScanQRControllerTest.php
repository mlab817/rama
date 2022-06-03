<?php

namespace Tests\Feature;

use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class ScanQRControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_validates_invalid_inputs()
    {
        $token = JWTAuth::fromUser(User::find(1));

        $response = $this
            ->withHeaders([
                'Content-Type'=>'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ])
            ->json('POST','/api/scan-qr', [
                'qrcode' => '',
                'date_scanned' => '',
                'time_scanned' => '',
                'station_id' => '',
                'bound' => '',
            ]);

        $response->assertStatus(422);
    }

    public function test_it_saves_valid_inputs()
    {
        $token = JWTAuth::fromUser(User::find(1));

        $qrcode = 'Some Operator/UVN576';

        $response = $this
            ->withHeaders([
                'Content-Type'=>'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ])
            ->json('POST', '/api/scan-qr', [
                'qrcode' => $qrcode,
                'date_scanned' => '2022-06-03',
                'time_scanned' => '14:00:00',
                'station_id' => 1,
                'bound' => 'NORTH',
            ]);

        $response->assertStatus(200);
    }
}
