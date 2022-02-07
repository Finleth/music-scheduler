<?php

namespace Tests\Feature;

use DateTime;
use Tests\TestCase;
use App\Models\Musician;
use App\Traits\ActingAsUser;
use App\Models\MusicianBlackout;

class MusicianBlackoutTest extends TestCase
{
    use ActingAsUser {
        setUp as actingAsUserSetUp;
    }

    /**
     * @var Musician
     */
    protected $musician;


    public function setUp(): void
    {
        $this->actingAsUserSetUp();

        $this->musician = Musician::factory()->create();
        $this->baseUrl = sprintf('musicians/%s/blackout', $this->musician->id);
    }

    /**
     * Test musician blackout list can be loaded and record displayed
     *
     * @return void
     */
    public function test_musician_blackout_list_screen_can_be_rendered_and_displays_record()
    {
        $musicianBlackout = MusicianBlackout::factory()->create([
            'musician_id' => $this->musician->id
        ]);

        $this->actingAs($this->actingAs)
            ->get(sprintf('musicians/%s/edit', $this->musician->id))
            ->assertStatus(200)
            ->assertSeeText($musicianBlackout->start->format(config('app.DISPLAY_DATE_FORMAT')));
    }

    /**
     * Test musician blackout form can be loaded
     *
     * @return void
     */
    public function test_musician_blackout_form_screen_can_be_rendered()
    {
        $this->actingAs($this->actingAs)
            ->get($this->getCreateUrl())
            ->assertStatus(200);
    }

    /**
     * Test new musician blackout can be created
     *
     * @return void
     */
    public function test_musician_blackout_can_be_created()
    {
        $start = new DateTime();
        $end = new DateTime('tomorrow');

        $this->actingAs($this->actingAs)
            ->followingRedirects()
            ->post($this->getCreateUrl(), [
                'start' => $start->format(config('app.INPUT_DATE_FORMAT')),
                'end' => $end->format(config('app.INPUT_DATE_FORMAT'))
            ])
            ->assertStatus(200)
            ->assertSeeText('The blackout was successfully added.');
    }

    /**
     * Test musician blackout edit form can be loaded
     *
     * @return void
     */
    public function test_musician_blackout_edit_form_screen_can_be_rendered()
    {
        $musicianBlackout = MusicianBlackout::factory()->create([
            'musician_id' => $this->musician->id
        ]);

        $this->actingAs($this->actingAs)
            ->get($this->getEditUrl($musicianBlackout))
            ->assertStatus(200);
    }

    /**
     * Test musician blackout can be edited
     *
     * @return void
     */
    public function test_musician_blackout_can_be_edited()
    {
        $musicianBlackout = MusicianBlackout::factory()->create([
            'musician_id' => $this->musician->id
        ]);
        $start = new DateTime();
        $end = new DateTime('tomorrow');

        $this->actingAs($this->actingAs)
            ->followingRedirects()
            ->post($this->getEditUrl($musicianBlackout), [
                'start' => $start->format(config('app.INPUT_DATE_FORMAT')),
                'end' => $end->format(config('app.INPUT_DATE_FORMAT'))
            ])
            ->assertStatus(200)
            ->assertSeeText('The blackout was successfully updated.');
    }

    /**
     * Test musician blackout can be deleted
     *
     * @return void
     */
    public function test_musician_blackout_can_be_deleted()
    {
        $musicianBlackout = MusicianBlackout::factory()->create([
            'musician_id' => $this->musician->id
        ]);

        $this->actingAs($this->actingAs)
            ->followingRedirects()
            ->get($this->getDeleteUrl($musicianBlackout))
            ->assertStatus(200)
            ->assertSeeText('The blackout was successfully deleted.');
    }
}
