<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Builder;

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
}
