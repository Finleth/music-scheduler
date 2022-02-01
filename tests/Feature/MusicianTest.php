<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Musician;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MusicianTest extends TestCase
{
    use RefreshDatabase;

    protected $baseUrl = '/musicians';

    /**
     * Confirm musician list view can be loaded and record is displayed
     *
     * @return void
     */
    public function test_musician_list_screen_can_be_rendered_and_displays_record()
    {
        $user = User::factory()->create();
        $musician = Musician::factory()->create();

        $this->actingAs($user)
            ->get($this->baseUrl)
            ->assertStatus(200)
            ->assertSeeText($musician->first_name);
    }

    /**
     * Confirm musician form can be loaded
     *
     * @return void
     */
    public function test_musician_form_screen_can_be_rendered()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get($this->baseUrl . '/new')
            ->assertStatus(200);
    }

    /**
     * Test new musician can be created
     */
    public function test_musician_can_be_created()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->followingRedirects()
            ->post($this->baseUrl . '/new', [
                'first_name' => 'Test',
                'last_name' => 'Musician',
                'status' => config('enums.status.ACTIVE'),
            ])
            ->assertStatus(200)
            ->assertSeeText('The musician was successfully added.');
    }
}
