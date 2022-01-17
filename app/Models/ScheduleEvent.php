<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class ScheduleEvent extends AbstractModel
{
    protected $table = 'schedule_events';
    protected $fillable = [
        'schedule_generation_id',
        'schedule_id',
        'schedule_event_type_id',
        'musician_id'
    ];

    /**
     * Get the schedule event's schedule.
     *
     * @return BelongsTo
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
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

    /**
     *
     * Scope a query to get a musician's most recent
     * schedule event of a certain type.
     *
     * @param Builder $query
     * @param integer $musicianId
     * @param integer $scheduleEventTypeId
     * @param integer $timeTreeCalendarId
     *
     * @return Builder
     */
    public function scopeMostRecentTypeForMusician(
        Builder $query,
        int $musicianId,
        int $scheduleEventTypeId,
        int $timeTreeCalendarId
    ) {
        return $query
            ->join('schedule', 'schedule.id', '=', 'schedule_events.schedule_id')
            ->where([
                'musician_id' => $musicianId,
                'schedule_event_type_id' => $scheduleEventTypeId,
                'schedule.time_tree_calendar_id' => $timeTreeCalendarId
            ])
            ->orderBy('schedule.event_date', 'DESC');
    }
}
