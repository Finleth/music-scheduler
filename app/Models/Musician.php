<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Builder;

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
            ->withPivot(['id', 'frequency', 'auto_schedule', 'schedule_week']);
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

    /**
     *
     * Scope a query to get active musicians without a blackout for a specific date
     *
     * @param Builder $query
     * @param DateTime $date
     *
     * @return Builder $query
     */
    public function scopeAvailable(
        Builder $query,
        DateTime $date
    )
    {
        $dateString = $date->format(config('app.DATE_FORMAT'));

        return $query->whereDoesntHave('blackouts', function($query) use ($dateString) {
            $query->where('start', '<=', $dateString)
                  ->where('end', '>=', $dateString);
        })->where([
            'status' => config('enums.status.ACTIVE')
        ]);
    }

    /**
     *
     * Scope a query to order musicians by their last and then first name
     *
     * @param Builder $query
     * @param string $direction
     *
     * @return Builder $query
     */
    public function scopeOrderByName(
        Builder $query,
        string $direction
    )
    {
        return $query->orderBy('last_name', $direction)->orderBy('first_name', $direction);
    }
}
