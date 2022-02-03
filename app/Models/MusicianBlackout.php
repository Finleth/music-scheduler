<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MusicianBlackout extends AbstractModel
{
    use HasFactory;

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
