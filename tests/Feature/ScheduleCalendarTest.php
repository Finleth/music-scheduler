<?php

namespace Tests\Feature;

use DateTime;
use Tests\TestCase;
use App\Models\Calendar;
use App\Models\Musician;
use App\Models\Schedule;
use App\Traits\ActingAsUser;
use App\Models\ScheduleEvent;
use App\Models\ScheduleEventType;

class ScheduleCalendarTest extends TestCase
{
    use ActingAsUser {
        setUp as actingAsUserSetUp;
    }

    /**
     * @var Calendar
     */
    protected $calendar;


    public function setUp(): void
    {
        $this->actingAsUserSetUp();

        $this->calendar = Calendar::factory()->create();
        $this->baseUrl = sprintf('schedule/%s', $this->calendar->id);
    }

    /**
     * Test calendar list can be loaded and record displayed
     *
     * @return void
     */
    public function test_calendar_list_screen_can_be_rendered_and_displays_record()
    {
        $this->actingAs($this->actingAs)
            ->get('schedule')
            ->assertStatus(200)
            ->assertSeeText($this->calendar->name);
    }

    /**
     * Test calendar schedule list can be loaded and record displayed
     *
     * @return void
     */
    public function test_calendar_schedule_list_screen_can_be_rendered_and_displays_record()
    {
        $schedule = Schedule::factory()->create(['time_tree_calendar_id' => $this->calendar->id]);
        $scheduleEventType = ScheduleEventType::factory()->create();
        $musician = Musician::factory()->create();
        $scheduleEvent = ScheduleEvent::factory()->create([
            'schedule_id' => $schedule->id,
            'schedule_event_type_id' => $scheduleEventType->id,
            'musician_id' => $musician->id
        ]);

        $this->actingAs($this->actingAs)
            ->get($this->baseUrl)
            ->assertStatus(200)
            ->assertSeeTextInOrder([
                $scheduleEvent->scheduleEventType->title,
                $scheduleEvent->musician->first_name
            ]);
    }

    /**
     * Test calendar schedule generation form can be rendered
     *
     * @return void
     */
    public function test_calendar_schedule_generation_form_screen_can_be_rendered()
    {
        $this->actingAs($this->actingAs)
            ->get($this->baseUrl . '/generate')
            ->assertStatus(200);
    }

    /**
     * Test calendar schedule generation form can be submitted
     *
     * @return void
     */
    public function test_calendar_schedule_generation_form_can_be_submitted()
    {
        $today = new DateTime();
        $nextWeek = new DateTime();
        $nextWeek->modify('+6 days');

        $this->actingAs($this->actingAs)
            ->followingRedirects()
            ->post($this->baseUrl . '/generate', [
                'start_date' => $today->format(config('app.DATE_FORMAT')),
                'end_date' => $nextWeek->format(config('app.DATE_FORMAT'))
            ])
            ->assertStatus(200)
            ->assertSeeText('The schedule was successfully generated.');
    }
}
