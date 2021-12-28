<?php

namespace App\Models;


class Schedule extends AbstractModel
{
    protected $table = 'schedule';
    protected $fillable = ['event_date'];
    protected $casts = [
        'event_date' => 'datetime'
    ];

    /**
     * Get the schedule's events.
     *
     * @return HasMany
     */
    public function events()
    {
        return $this->hasMany(ScheduleEvent::class);
    }
}
