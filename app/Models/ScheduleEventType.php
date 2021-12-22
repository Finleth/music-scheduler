<?php

namespace App\Models;


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
}
