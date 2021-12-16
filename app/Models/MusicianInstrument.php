<?php

namespace App\Models;


class MusicianInstrument extends AbstractModel
{
    protected $table = 'musician_instruments';
    protected $fillable = ['musician_id', 'name', 'primary'];

    /**
     * Get the instruments's musician.
     *
     * @return BelongsTo
     */
    public function musician()
    {
        return $this->belongsTo(Musician::class);
    }
}
