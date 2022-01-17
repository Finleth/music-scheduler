<?php

namespace App\Models;

class ScheduleGeneration extends AbstractModel
{
    protected $table = 'schedule_generations';
    protected $fillable = ['batch', 'events_created'];
}
