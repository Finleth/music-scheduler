<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class ScheduleGeneration extends AbstractModel
{
    protected $table = 'schedule_generations';
    protected $fillable = [
        'time_tree_calendar_id',
        'batch',
        'events_created'
    ];

    /**
     * Get the schedule generation's calendar.
     *
     * @return BelongsTo
     */
    public function calendar()
    {
        return $this->belongsTo(Calendar::class, 'time_tree_calendar_id', 'id');
    }

    /**
     *
     * Scope a query to return schedule_generations by the associated calendars
     *
     * @param Builder $query
     * @param integer $id
     *
     * @return Builder
     */
    public function scopeOfCalendar(Builder $query, int $id)
    {
        return $query->where($this->table . '.time_tree_calendar_id', $id);
    }
}
