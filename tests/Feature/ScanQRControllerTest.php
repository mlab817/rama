<?php

namespace Tests\Feature;

use App\Models\Trip;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ScanQRControllerTest extends TestCase
{
    public function truncateTables()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('trips')->truncate();
        DB::table('puv_details')->truncate();
        Schema::enableForeignKeyConstraints();
    }

    public function test_it_validates_inputs()
    {
        $response = $this->json('POST', route('api.scan-qr'), [
            'plate_no' => null,
            "date_scanned" => null,
            "time_scanned" => null,
            "station_id" => null,
            "bound" => null,
            "trip" => null,
            "user_id" => null
        ]);

        $response->assertJsonValidationErrorFor('plate_no')
            ->assertJsonValidationErrorFor('date_scanned')
            ->assertJsonValidationErrorFor('time_scanned')
            ->assertJsonValidationErrorFor('station_id')
            ->assertJsonValidationErrorFor('bound')
            ->assertJsonValidationErrorFor('trip')
            ->assertJsonValidationErrorFor('user_id');
    }

    public function test_it_validates_plate_no_existence()
    {
        $response = $this->json('POST', route('api.scan-qr'), [
            'plate_no' => 'RANDOM',
            "date_scanned" => "2022-06-09",
            "time_scanned" => "15:00:00",
            "station_id" => 1,
            "bound" => "NORTH",
            "trip" => "END",
            "user_id" => 1
        ]);

        $response->assertStatus(422)
            ->assertJson(function (AssertableJson $json) {
                $json->has('status')
                    ->has('message');
            });
    }

    public function test_it_saves_valid_inputs()
    {
        $data = [
            "plate_no" => "ABC123",
            "date_scanned" => "2022-06-09",
            "time_scanned" => "15:00:00",
            "station_id" => 1,
            "bound" => "NORTH",
            "trip" => "END",
            "user_id" => 1
        ];

        $response = $this
            ->json('POST', '/api/scan-qr', $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('puv_details', $data);

        $this->truncateTables();
    }

    public function test_it_creates_trip_entry_for_valid_start()
    {
        $data = [
            "plate_no" => "ABC123",
            "date_scanned" => "2022-06-09",
            "time_scanned" => "15:00:00",
            "station_id" => 1,
            "bound" => "NORTH",
            "trip" => "START",
            "user_id" => 1
        ];

        $response = $this
            ->json('POST', '/api/scan-qr', $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('trips', [
            'plate_no' => $data['plate_no'],
            'start_date' => $data['date_scanned'],
            'start_time' => $data['time_scanned'],
            'start_station_id' => $data['station_id'],
            'start_user_id' => $data['user_id'],
        ]);

        $this->truncateTables();
    }

    public function test_it_creates_trip_entry_for_valid_end()
    {
        $startTrip = Trip::create([
            "plate_no" => "ABC123",
            "start_date" => "2022-06-10",
            "start_time" => "15:00:00",
            "start_station_id" => 1,
            "bound" => "NORTH",
            "start_user_id" => 1
        ]);

        $data = [
            "plate_no" => "ABC123",
            "date_scanned" => "2022-06-10",
            "time_scanned" => "15:00:00",
            "station_id" => 1,
            "bound" => "NORTH",
            "trip" => "END",
            "user_id" => 1
        ];

        $response = $this
            ->json('POST', '/api/scan-qr', $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('trips', [
            'id' => $startTrip->id,
            'plate_no' => $data['plate_no'],
            'end_date' => $data['date_scanned'],
            'end_time' => $data['time_scanned'],
            'end_station_id' => $data['station_id'],
            'end_user_id' => $data['user_id'],
        ]);

        $this->truncateTables();
    }

    public function test_it_does_not_create_new_trip_when_input_has_no_start()
    {
        $data = [
            "plate_no" => "ABC123",
            "date_scanned" => "2022-06-10",
            "time_scanned" => "15:00:00",
            "station_id" => 1,
            "bound" => "NORTH",
            "trip" => "END",
            "user_id" => 1
        ];

        $response = $this
            ->json('POST', '/api/scan-qr', $data);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('trips', [
            'plate_no' => $data['plate_no'],
            'end_date' => $data['date_scanned'],
            'end_time' => $data['time_scanned'],
            'end_station_id' => $data['station_id'],
            'end_user_id' => $data['user_id'],
        ]);

        $this->truncateTables();
    }
}
