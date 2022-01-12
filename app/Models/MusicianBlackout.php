<?php

namespace App\Models;


class MusicianBlackout extends AbstractModel
{
    protected $table = 'musician_blackouts';
    protected $fillable = ['musician_id', 'start', 'end'];
    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime'
    ];

    /**
     * Get the blackout's musician.
     *
     * @return BelongsTo
     */
    public function musician()
    {
        return $this->belongsTo(Musician::class);
    }
}
