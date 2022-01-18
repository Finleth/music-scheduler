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
        $relation = $this->hasMany(ScheduleEvent::class);

        if (request()->has('batch')) {
            $batch = (int) request()->input('batch');

            $relation->ofBatch($batch);
        }

        return $relation;
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
     * Scope a query to return schedule rows for a specific time frame
     *
     * @param Builder $query
     * @param DateTime $start
     * @param DateTime $end
     *
     * @return Builder
     */
    public function scopeEventDateBetween(
        Builder $query,
        DateTime $start = null,
        DateTime $end = null
    )
    {
        if ($start) {
            $query->where($this->table . '.event_date', '>=', $start->format(config('app.DATE_FORMAT')));
        }

        if ($end) {
            $query->where($this->table . '.event_date', '<=', $end->format(config('app.DATE_FORMAT')));
        }

        return $query;
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
        return $query->where($this->table . '.time_tree_calendar_id', $id);
    }

    /**
     *
     * Scope a query to return schedule rows that contain events of a certain batch
     *
     * @param Builder $query
     * @param integer $batch
     *
     * @return Builder
     */
    public function scopeOfBatch(Builder $query, int $batch = null)
    {
        if ($batch) {
            $query->whereHas('events.schedule_generation', function($query) use ($batch) {
                $query->where('schedule_generations.batch', $batch);
            });
        }

        return $query;
    }
}
