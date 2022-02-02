<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Traits\ActingAsUser;
use App\Models\ScheduleEventType;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScheduleEventTypeTest extends TestCase
{
    use RefreshDatabase, ActingAsUser;

    protected $baseUrl = '/schedule-event-types';

    /**
     * Test Schedule Event Types list can be loaded and displays record
     *
     * @return void
     */
    public function test_schedule_event_type_list_screen_can_be_rendered_and_displays_record()
    {
        $scheduleEventType = ScheduleEventType::factory()->create();

        $this->actingAs($this->actingAs)
            ->get($this->baseUrl)
            ->assertStatus(200)
            ->assertSeeText($scheduleEventType->title);
    }

    /**
     * Test Schedule Event Types form can be loaded
     *
     * @return void
     */
    public function test_schedule_event_type_form_screen_can_be_rendered()
    {
        $this->actingAs($this->actingAs)
            ->get($this->getCreateUrl())
            ->assertStatus(200);
    }

    /**
     * Test Schedule Event Types form can be submitted
     *
     * @return void
     */
    public function test_schedule_event_type_form_can_be_submitted()
    {
        $this->actingAs($this->actingAs)
            ->followingRedirects()
            ->post($this->getCreateUrl(), [
                'title' => 'Sunday Morning',
                'time' => '11:00',
                'day_of_week' => 0,
                'first_of_month' => config('enums.NO')
            ])
            ->assertStatus(200)
            ->assertSee('The event type was successfully added.');
    }

    /**
     * Test Schedule Event Types form can be loaded
     *
     * @return void
     */
    public function test_schedule_event_type_edit_form_screen_can_be_rendered()
    {
        $scheduleEventType = ScheduleEventType::factory()->create();

        $this->actingAs($this->actingAs)
            ->get($this->getEditUrl($scheduleEventType))
            ->assertStatus(200);
    }

    /**
     * Test Schedule Event Types list can be loaded and displays record
     *
     * @return void
     */
    public function test_schedule_event_type_edit_form_can_be_submitted()
    {
        $scheduleEventType = ScheduleEventType::factory()->create();

        $this->actingAs($this->actingAs)
            ->followingRedirects()
            ->post($this->getEditUrl($scheduleEventType), [
                'title' => '1st Prayer Meeting',
                'time' => '19:00',
                'day_of_week' => 3,
                'first_of_month' => config('enums.YES')
            ])
            ->assertStatus(200)
            ->assertSee('The event type was successfully updated.');
    }

    /**
     * Test Schedule Event Types list can be loaded and displays record
     *
     * @return void
     */
    public function test_schedule_event_type_can_be_deleted()
    {
        $scheduleEventType = ScheduleEventType::factory()->create();

        $this->actingAs($this->actingAs)
            ->followingRedirects()
            ->get($this->getDeleteUrl($scheduleEventType))
            ->assertStatus(200)
            ->assertSee('The event type was successfully deleted.');
    }
}
