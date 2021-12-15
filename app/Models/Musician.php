<?php

namespace App\Models;


class Musician extends AbstractModel
{
    protected $table = 'musicians';
    protected $fillable = ['first_name', 'last_name', 'status'];

    /**
     * Get the musicians's instruments.
     *
     * @return HasMany
     */
    public function instruments()
    {
        return $this->hasMany(MusicianInstrument::class);
    }
}
