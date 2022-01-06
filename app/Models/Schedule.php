<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Builder;

class Schedule extends AbstractModel
{
    protected $table = 'schedule';
    protected $fillable = ['time_tree_calendar_id', 'event_date'];
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

    /**
     * Get the schedule's calendar.
     *
     * @return BelongsTo
     */
    public function calendar()
    {
        return $this->belongsTo(Calendar::class, 'time_tree_calendar_id', 'id');
    }

    /**
     *
     * Scope a query to return schedule rows for today or in the future
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeFutureEvents(Builder $query)
    {
        $today = new DateTime();
        return $query->where('event_date', '>=', $today->format(config('app.DATE_FORMAT')));
    }

    /**
     *
     * Scope a query to return schedule rows by time_tree_calendar_id
     *
     * @param Builder $query
     * @param integer $id
     *
     * @return Builder
     */
    public function scopeOfCalendar(Builder $query, int $id)
    {
        return $query->where('time_tree_calendar_id', $id);
    }
}
