<?php

namespace App\Traits;

use App\Models\User;

trait ActingAsUser
{
    /**
     * @var App\Models\User
     */
    protected $actingAs;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs = User::factory()->create();
    }
}
