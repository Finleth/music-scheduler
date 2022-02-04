<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MusicianInstrument extends AbstractModel
{
    use HasFactory;

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
