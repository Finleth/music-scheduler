<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Musician;
use App\Traits\ActingAsUser;
use App\Models\MusicianInstrument;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MusicianInstrumentTest extends TestCase
{
    use RefreshDatabase;
    use ActingAsUser {
        setUp as actingAsUserSetUp;
    }

    protected $baseUrl;

    /**
     * @var Musician
     */
    protected $musician;


    public function setUp(): void
    {
        $this->actingAsUserSetUp();

        $this->musician = Musician::factory()->create();
        $this->baseUrl = sprintf('musicians/%s/instrument', $this->musician->id);
    }

    /**
     * Test musician instrument list can be loaded and record displayed
     *
     * @return void
     */
    public function test_musician_instrument_list_screen_can_be_rendered_and_displays_record()
    {
        $musicianInstrument = MusicianInstrument::factory()->primary()->create([
            'musician_id' => $this->musician->id
        ]);

        $this->actingAs($this->actingAs)
            ->get(sprintf('musicians/%s/edit', $this->musician->id))
            ->assertStatus(200)
            ->assertSeeText($musicianInstrument->name);
    }

    /**
     * Test musician instrument form can be loaded
     *
     * @return void
     */
    public function test_musician_instrument_form_screen_can_be_rendered()
    {
        $this->actingAs($this->actingAs)
            ->get($this->getCreateUrl())
            ->assertStatus(200);
    }

    /**
     * Test new musician instrument can be created
     *
     * @return void
     */
    public function test_musician_instrument_can_be_created()
    {
        $this->actingAs($this->actingAs)
            ->followingRedirects()
            ->post($this->getCreateUrl(), [
                'name' => 'Piano',
                'primary' => config('enums.YES')
            ])
            ->assertStatus(200)
            ->assertSeeText('The instrument was successfully added.');
    }

    /**
     * Test musician instrument edit form can be loaded
     *
     * @return void
     */
    public function test_musician_instrument_edit_form_screen_can_be_rendered()
    {
        $musicianInstrument = MusicianInstrument::factory()->create([
            'musician_id' => $this->musician->id
        ]);

        $this->actingAs($this->actingAs)
            ->get($this->getEditUrl($musicianInstrument))
            ->assertStatus(200);
    }

    /**
     * Test musician instrument can be edited
     *
     * @return void
     */
    public function test_musician_instrument_can_be_edited()
    {
        $musicianInstrument = MusicianInstrument::factory()->create([
            'musician_id' => $this->musician->id
        ]);

        $this->actingAs($this->actingAs)
            ->followingRedirects()
            ->post($this->getEditUrl($musicianInstrument), [
                'name' => 'Guitar',
                'primary' => config('enums.NO')
            ])
            ->assertStatus(200)
            ->assertSeeText('The instrument was successfully updated.');
    }

    /**
     * Test musician instrument can be deleted
     *
     * @return void
     */
    public function test_musician_instrument_can_be_deleted()
    {
        $musicianInstrument = MusicianInstrument::factory()->create([
            'musician_id' => $this->musician->id
        ]);

        $this->actingAs($this->actingAs)
            ->followingRedirects()
            ->get($this->getDeleteUrl($musicianInstrument))
            ->assertStatus(200)
            ->assertSeeText('The instrument was successfully deleted.');
    }
}
