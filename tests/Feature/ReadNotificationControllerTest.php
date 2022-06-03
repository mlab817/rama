<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReadNotificationControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $notificationId = '78f2639c-c91a-42de-a51a-6a22ffd02b1a';

        $response = $this
            ->actingAs(User::find(1))
            ->post(route('notifications.read'), [
            'notification_id' => $notificationId
        ]);

        $response->assertStatus(200);
    }
}
