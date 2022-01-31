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

        $this->actingAs($user)
            ->get($this->baseUrl)
            ->assertStatus(200)
            ->assertSeeText($scheduleEventType->title);
    }

    /**
     * Confirm Schedule Event Types form can be loaded
     *
     * @return void
     */
    public function test_schedule_event_type_form_screen_can_be_rendered()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get($this->baseUrl . '/new')
            ->assertStatus(200);
    }

    /**
     * Confirm Schedule Event Types form can be submitted
     *
     * @return void
     */
    public function test_schedule_event_type_form_can_be_submitted()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->followingRedirects()
            ->post($this->baseUrl . '/new', [
                'title' => 'Sunday Morning',
                'time' => '11:00',
                'day_of_week' => 0,
                'first_of_month' => config('enums.NO')
            ])
            ->assertStatus(200)
            ->assertSee('The event type was successfully added.');
    }

    /**
     * Confirm Schedule Event Types form can be loaded
     *
     * @return void
     */
    public function test_schedule_event_type_edit_form_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        $scheduleEventType = ScheduleEventType::factory()->create();

        $this->actingAs($user)
            ->get($this->baseUrl . '/' . $scheduleEventType->id . '/edit')
            ->assertStatus(200);
    }

    /**
     * Confirm Schedule Event Types list can be loaded and displays record
     *
     * @return void
     */
    public function test_schedule_event_type_edit_form_can_be_submitted()
    {
        $user = User::factory()->create();
        $scheduleEventType = ScheduleEventType::factory()->create();
        $editUrl = $this->baseUrl . '/' . $scheduleEventType->id . '/edit';

        $this->actingAs($user)
            ->followingRedirects()
            ->post($editUrl, [
                'title' => '1st Prayer Meeting',
                'time' => '19:00',
                'day_of_week' => 3,
                'first_of_month' => config('enums.YES')
            ])
            ->assertStatus(200)
            ->assertSee('The event type was successfully updated.');
    }

    /**
     * Confirm Schedule Event Types list can be loaded and displays record
     *
     * @return void
     */
    public function test_schedule_event_type_can_be_deleted()
    {
        $user = User::factory()->create();
        $scheduleEventType = ScheduleEventType::factory()->create();

        $this->actingAs($user)
            ->followingRedirects()
            ->get($this->baseUrl . '/' . $scheduleEventType->id . '/delete')
            ->assertStatus(200)
            ->assertSee('The event type was successfully deleted.');;
    }
}
