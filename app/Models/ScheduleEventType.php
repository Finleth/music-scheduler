<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;


class ScheduleEventType extends AbstractModel
{
    protected $table = 'schedule_event_types';
    protected $fillable = [
        'title',
        'minute',
        'hour',
        'day_of_month',
        'month',
        'day_of_week',
        'first_of_month'
    ];

    /**
     * Get the event type's available musicians.
     */
    public function musicians()
    {
        return $this->belongsToMany(Musician::class, 'musicians_schedule_event_types')
            ->withPivot(['frequency']);
    }

    /**
     * Scope a query to get all the available events for a musician to be assigned
     *
     * @param Builder $query
     * @param integer $musicianId
     * @param integer $exceptionId
     */
    public function scopeAvailableEvents(Builder $query, int $musicianId, int $exceptionId = null)
    {
        $assignedEvents = MusicianScheduleEventType::where('musician_id', $musicianId)->get();
        $assignedEventIds = [];

        foreach ($assignedEvents as $event) {
            if ($exceptionId && $event->schedule_event_type_id === $exceptionId) {
                continue;
            }

            $assignedEventIds[] = $event->schedule_event_type_id;
        }

        return $query->whereNotIn('schedule_event_types.id', $assignedEventIds);
    }
}
