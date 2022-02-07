<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Musician;
use App\Models\MusicianScheduleEventType;
use App\Models\ScheduleEventType;
use App\Traits\ActingAsUser;

class MusicianScheduleEventTypeTest extends TestCase
{
    use ActingAsUser {
        setUp as actingAsUserSetUp;
    }

    /**
     * @var Musician
     */
    protected $musician;

    /**
     * @var ScheduleEventType
     */
    protected $scheduleEventType;


    public function setUp(): void
    {
        $this->actingAsUserSetUp();

        $this->musician = Musician::factory()->create();
        $this->scheduleEventType = ScheduleEventType::factory()->create();

        $this->baseUrl = sprintf('musicians/%s/event', $this->musician->id);
    }

    /**
     * Test musician schedule event type list can be loaded and record displayed
     *
     * @return void
     */
    public function test_musician_schedule_event_type_list_screen_can_be_rendered_and_displays_record()
    {
        $musicianEvent = MusicianScheduleEventType::factory()->create([
            'musician_id' => $this->musician->id,
            'schedule_event_type_id' => $this->scheduleEventType->id
        ]);

        $this->actingAs($this->actingAs)
            ->get(sprintf('musicians/%s/edit', $this->musician->id))
            ->assertStatus(200)
            ->assertSeeText($this->scheduleEventType->title);

        $musicianEvent->forceDelete();
    }

    /**
     * Test musician schedule event type form can be loaded
     *
     * @return void
     */
    public function test_musician_schedule_event_type_form_screen_can_be_rendered()
    {
        $this->actingAs($this->actingAs)
            ->get($this->getCreateUrl())
            ->assertStatus(200);
    }

    /**
     * Test new musician schedule event type can be created
     *
     * @return void
     */
    public function test_musician_schedule_event_type_can_be_created()
    {
        $this->actingAs($this->actingAs)
            ->followingRedirects()
            ->post($this->getCreateUrl(), [
                'schedule_event_type_id' => $this->scheduleEventType->id,
                'frequency' => 100,
                'auto_schedule' => config('enums.YES')
            ])
            ->assertStatus(200)
            ->assertSeeText('The event was successfully added.');
    }

    /**
     * Test musician schedule event type edit form can be loaded
     *
     * @return void
     */
    public function test_musician_schedule_event_type_edit_form_screen_can_be_rendered()
    {
        $musicianEvent = MusicianScheduleEventType::factory()->create([
            'musician_id' => $this->musician->id,
            'schedule_event_type_id' => $this->scheduleEventType->id
        ]);

        $this->actingAs($this->actingAs)
            ->get($this->getEditUrl($musicianEvent))
            ->assertStatus(200);

        $musicianEvent->forceDelete();
    }

    /**
     * Test musician schedule event type can be edited
     *
     * @return void
     */
    public function test_musician_schedule_event_type_can_be_edited()
    {
        $musicianEvent = MusicianScheduleEventType::factory()->create([
            'musician_id' => $this->musician->id,
            'schedule_event_type_id' => $this->scheduleEventType->id
        ]);

        $this->actingAs($this->actingAs)
            ->followingRedirects()
            ->post($this->getEditUrl($musicianEvent), [
                'schedule_event_type_id' => $this->scheduleEventType->id,
                'frequency' => 50,
                'auto_schedule' => config('enums.NO')
            ])
            ->assertStatus(200)
            ->assertSeeText('The event was successfully updated.');

        $musicianEvent->forceDelete();
    }

    /**
     * Test musician schedule event type can be deleted
     *
     * @return void
     */
    public function test_musician_schedule_event_type_can_be_deleted()
    {
        $musicianEvent = MusicianScheduleEventType::factory()->create([
            'musician_id' => $this->musician->id,
            'schedule_event_type_id' => $this->scheduleEventType->id
        ]);

        $this->actingAs($this->actingAs)
            ->followingRedirects()
            ->get($this->getDeleteUrl($musicianEvent))
            ->assertStatus(200)
            ->assertSeeText('The event was successfully deleted.');
    }
}
