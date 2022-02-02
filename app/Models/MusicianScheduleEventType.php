<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MusicianScheduleEventType extends AbstractModel
{
    use HasFactory;

    protected $table = 'musicians_schedule_event_types';
    protected $fillable = [
        'musician_id',
        'schedule_event_type_id',
        'frequency',
        'auto_schedule',
        'schedule_week'
    ];

    /**
     * Get the pivot table's musician.
     *
     * @return BelongsTo
     */
    public function musician()
    {
        return $this->belongsTo(Musician::class);
    }

    /**
     * Get the pivot table's schedule event type.
     *
     * @return BelongsTo
     */
    public function scheduleEventType()
    {
        return $this->belongsTo(ScheduleEventType::class);
    }
}
