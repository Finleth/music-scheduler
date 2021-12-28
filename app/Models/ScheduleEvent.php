<?php

namespace App\Models;


class ScheduleEvent extends AbstractModel
{
    protected $table = 'schedule_events';
    protected $fillable = ['schedule_id', 'schedule_event_type_id', 'musician_id'];

    /**
     * Get the schedule event's schedule.
     *
     * @return BelongsTo
     */
    public function schedule()
    {
        return $this->belongsTo(ScheduleEvent::class);
    }

    /**
     * Get the schedule event's type.
     *
     * @return BelongsTo
     */
    public function schedule_event_type()
    {
        return $this->belongsTo(ScheduleEventType::class);
    }

    /**
     * Get the schedule event's musician.
     *
     * @return BelongsTo
     */
    public function musician()
    {
        return $this->belongsTo(Musician::class);
    }
}
