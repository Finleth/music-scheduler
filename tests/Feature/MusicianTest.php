<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Traits\ActingAsUser;
use App\Models\Musician;

class MusicianTest extends TestCase
{
    use ActingAsUser;

    protected $baseUrl = '/musicians';

    /**
     * Test musician list view can be loaded and record is displayed
     *
     * @return void
     */
    public function test_musician_list_screen_can_be_rendered_and_displays_record()
    {
        $musician = Musician::factory()->create();

        $this->actingAs($this->actingAs)
            ->get($this->baseUrl)
            ->assertStatus(200)
            ->assertSeeText($musician->first_name);
    }

    /**
     * Test musician form can be loaded
     *
     * @return void
     */
    public function test_musician_form_screen_can_be_rendered()
    {
        $this->actingAs($this->actingAs)
            ->get($this->getCreateUrl())
            ->assertStatus(200);
    }

    /**
     * Test new musician can be created
     *
     * @return void
     */
    public function test_musician_can_be_created()
    {
        $this->actingAs($this->actingAs)
            ->followingRedirects()
            ->post($this->getCreateUrl(), [
                'first_name' => 'Test',
                'last_name' => 'Musician',
                'status' => config('enums.status.ACTIVE'),
            ])
            ->assertStatus(200)
            ->assertSeeText('The musician was successfully added.');
    }

    /**
     * Test musician edit form can be loaded
     *
     * @return void
     */
    public function test_musician_edit_form_screen_can_be_rendered()
    {
        $musician = Musician::factory()->create();

        $this->actingAs($this->actingAs)
            ->get($this->getEditUrl($musician))
            ->assertStatus(200);
    }

    /**
     * Test musician can be edited
     *
     * @return void
     */
    public function test_musician_can_be_edited()
    {
        $musician = Musician::factory()->create();

        $this->actingAs($this->actingAs)
            ->followingRedirects()
            ->post($this->getEditUrl($musician), [
                'first_name' => 'Test',
                'last_name' => 'Musician',
                'status' => config('enums.status.INACTIVE')
            ])
            ->assertStatus(200)
            ->assertSeeText('The musician was successfully updated.');
    }
}
