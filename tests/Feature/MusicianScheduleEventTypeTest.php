<?php

namespace Tests\Feature;

use App\Traits\ActingAsUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MusicianScheduleEventTypeTest extends TestCase
{
    use RefreshDatabase, ActingAsUser;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
