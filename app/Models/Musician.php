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

    /**
     * Get all of the musician's event types.
     */
    public function schedule_event_types()
    {
        return $this->belongsToMany(ScheduleEventType::class, 'musicians_schedule_event_types')
            ->withPivot(['id', 'frequency']);
    }

    /**
     * Get all of the musician's events.
     *
     * @return HasMany
     */
    public function schedule_events()
    {
        return $this->hasMany(ScheduleEvent::class);
    }

    /**
     * Get the musicians's blackouts.
     *
     * @return HasMany
     */
    public function blackouts()
    {
        return $this->hasMany(MusicianBlackout::class);
    }
}
