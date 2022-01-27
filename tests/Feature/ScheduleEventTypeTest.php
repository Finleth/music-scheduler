<?php

namespace Tests\Feature;

use App\Models\ScheduleEventType;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScheduleEventTypeTest extends TestCase
{
    use RefreshDatabase;

    protected $baseUrl = '/schedule-event-types';

    /**
     * Confirm Schedule Event Types list can be loaded and displays record
     *
     * @return void
     */
    public function test_schedule_event_type_list_screen_can_be_rendered_and_displays_record()
    {
        $user = User::factory()->create();
        $scheduleEventType = ScheduleEventType::factory()->create();

        $response = $this->actingAs($user)
            ->get($this->baseUrl);

        $response->assertStatus(200);
        $response->assertSeeText($scheduleEventType->title);
    }

    /**
     * Confirm Schedule Event Types form can be loaded
     *
     * @return void
     */
    public function test_schedule_event_type_form_screen_can_be_rendered()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get($this->baseUrl . '/new');

        $response->assertStatus(200);
    }

    /**
     * Confirm Schedule Event Types form can be submitted
     *
     * @return void
     */
    public function test_schedule_event_type_form_can_be_submitted()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post($this->baseUrl . '/new', ['title' => 'Sunday Morning',
            'time' => '11:00',
            'day_of_week' => 0,
            'first_of_month' => config('enums.NO')
        ]);

        $response->assertRedirect($this->baseUrl);
    }
}
