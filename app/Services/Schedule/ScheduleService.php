<?php

namespace App\Services\Schedule;

use DateTime;
use Exception;
use DateInterval;
use App\Models\Musician;
use App\Models\Schedule;
use App\Models\ScheduleEvent;
use App\Models\ScheduleEventType;
use App\Models\ScheduleGeneration;
use Illuminate\Support\Facades\DB;

/**
 *
 * Class ScheduleService
 * @package App\Services
 */
class ScheduleService
{
    protected $logger;
    protected $defaultWeight = 10;
    protected $defaultMultiplier = 10;
    protected $dayInterval;
    protected $dateFormat;
    protected $scheduleEventStart;
    protected $scheduleEventEnd;
    protected $timeTreeService;

    /**
     * ScheduleService's class constructor
     */
    public function __construct()
    {
        $this->logger = app('log');
        $this->dayInterval = new DateInterval('P1D');
        $this->dateFormat = config('app.DATE_FORMAT');
        $this->timeFormat = config('app.TIME_FORMAT');
    }

    /**
     *
     * @param integer $calendarId
     * @param string $startDate
     * @param string $endDate
     * @param ScheduleEventType $scheduleEventType
     *
     * @return array
     */
    public function generateSchedule(
        int $calendarId,
        string $startDate,
        string $endDate,
        ScheduleEventType $scheduleEventType = null
    )
    {
        $response = [
            'success' => false,
            'error' => null,
            'batch' => null
        ];

        try {
            $scheduleEventTypes = [];

            if ($scheduleEventType) {
                $scheduleEventTypes[] = $scheduleEventType;
            } else {
                $scheduleEventTypes = ScheduleEventType::all();
            }

            $currentDate = new DateTime($startDate);
            $endDate = new DateTime($endDate);

            DB::beginTransaction();

            $scheduleGeneration = $this->createScheduleGeneration($calendarId);

            while ($currentDate->format($this->dateFormat) <= $endDate->format($this->dateFormat)) {
                foreach ($scheduleEventTypes as $type) {
                    $this->generateScheduleRecord(
                        $calendarId,
                        $type,
                        $currentDate,
                        $scheduleGeneration
                    );
                }

                $currentDate->add($this->dayInterval);
            }

            DB::commit();
            $response['success'] = true;
            $response['batch'] = $scheduleGeneration->batch;

        } catch (Exception $e) {
            $this->logger->warning($e->getMessage());

            DB::rollback();
            $this->logger->warning('Rolling back schedule generation.');
        }

        return $response;
    }

    /**
     *
     * @param integer $timeTreeCalendarId
     * @param ScheduleEventType $type
     * @param DateTime $currentDate
     * @param ScheduleGeneration $scheduleGeneration
     *
     * @throws Exception
     * @return void
     */
    private function generateScheduleRecord(
        int $timeTreeCalendarId,
        ScheduleEventType $type,
        DateTime $currentDate,
        ScheduleGeneration $scheduleGeneration
    )
    {
        try {
            if ($type->day_of_week === $currentDate->format('w')) {
                // skip if current date isn't first of the month and the event type requires it
                if ($type->first_of_month === config('enums.YES')
                    && (int) $currentDate->format('j') > 7
                ) {
                    return;
                }

                $scheduleDate = Schedule::firstOrCreate([
                    'time_tree_calendar_id' => $timeTreeCalendarId,
                    'event_date' => $currentDate->format($this->dateFormat)
                ]);

                // skip if event already exists
                $eventExists = ScheduleEvent::where([
                    'schedule_id' => $scheduleDate->id,
                    'schedule_event_type_id' => $type->id
                ])->first();

                if (!$eventExists) {
                    $musician = $this->getMusicianToAssign($type, $scheduleDate);

                    if ($type && $scheduleDate && $musician) {
                        $this->createScheduleEvent(
                            $type,
                            $scheduleDate,
                            $musician,
                            $scheduleGeneration
                        );
                    } else {
                        $this->logger->warning(sprintf(
                            'Unable to create schedule event for %s on %s',
                            $type->title,
                            $currentDate->format($this->dateFormat)
                        ));
                    }
                }
            }
        } catch (Exception $e) {
            $this->logger->warning($e->getMessage());

            throw $e;
        }
    }

    /**
     *
     * @param ScheduleEventType $type
     * @param Schedule $scheduleDate
     *
     * @return Musician|null
     */
    private function getMusicianToAssign(
        ScheduleEventType $type,
        Schedule $scheduleDate
    )
    {
        try {
            $musicianWeights = [];
            $pickedMusician = [
                'musician' => null,
                'weight' => 0
            ];
            $musicians = $type->musicians()->available($scheduleDate->event_date)->get();

            foreach ($musicians as $musician) {
                // select musician if the have a forced assigned for that week
                if ((int) $musician->pivot->schedule_week === (int) ceil((int)$scheduleDate->event_date->format('j') / 7)) {
                    return $musician;
                }

                // skip any musicians not set to be automatically scheduled
                if ($musician->pivot->auto_schedule === config('enums.NO')) {
                    continue;
                }

                $scheduleEvent = ScheduleEvent::mostRecentTypeForMusician(
                    $musician->id,
                    $type->id,
                    $scheduleDate->time_tree_calendar_id
                )->first();

                $frequency = $musician->pivot->frequency / 100;

                if ($scheduleEvent) {
                    $dateDiff = $scheduleDate->event_date->diff($scheduleEvent->schedule->event_date);
                    $weeks = floor($dateDiff->days / 7);
                    $musicianWeights[$musician->id] = [
                        'musician' => $musician,
                        'weight' => $this->defaultWeight * $weeks * $frequency
                    ];
                } else {
                    $musicianWeights[$musician->id] = [
                        'musician' => $musician,
                        'weight' => $this->defaultWeight * $this->defaultMultiplier * $frequency
                    ];
                }

                // if musician already scheduled for a different event that day, lower their priority
                $sameDayEvent = $musician->scheduleEvents()->where([
                    'schedule_id' => $scheduleDate->id
                ])->first();

                if ($sameDayEvent) {
                    $musicianWeights[$musician->id]['weight'] /= $this->defaultMultiplier;
                }

            }

            foreach ($musicianWeights as $musicianWeight) {
                if ($pickedMusician['weight'] <= $musicianWeight['weight']) {
                    $pickedMusician['musician'] = $musicianWeight['musician'];
                    $pickedMusician['weight'] = $musicianWeight['weight'];
                }
            }

            return $pickedMusician['musician'];

        } catch (Exception $e) {
            $this->logger->warning($e->getMessage());
        }
    }

    /**
     *
     * @param ScheduleEventType $type
     * @param Schedule $scheduleDate
     * @param Musician $musician
     * @param ScheduleGeneration $scheduleGeneration
     *
     * @throws Exception
     * @return ScheduleEvent
     */
    private function createScheduleEvent(
        ScheduleEventType $type,
        Schedule $scheduleDate,
        Musician $musician,
        ScheduleGeneration $scheduleGeneration
    )
    {
        try {
            $scheduleEvent = ScheduleEvent::firstOrCreate(
                [
                    'schedule_event_type_id' => $type->id,
                    'schedule_id' => $scheduleDate->id
                ],
                [
                    'schedule_generation_id' => $scheduleGeneration->id,
                    'musician_id' => $musician->id
                ]
            );

            $scheduleGeneration->events_created++;
            $scheduleGeneration->save();

            return $scheduleEvent;
        } catch (Exception $e) {
            $this->logger->warning($e->getMessage());

            throw $e;
        }
    }

    /**
     *
     * @param integer $calendarId
     *
     * @return ScheduleGeneration
     */
    private function createScheduleGeneration(int $calendarId)
    {
        try {
            $latestScheduleGeneration = ScheduleGeneration::ofCalendar($calendarId)
                ->orderBy('batch', 'DESC')
                ->first();
            $batch = ($latestScheduleGeneration->batch ?? 0) + 1;

            return ScheduleGeneration::create([
                'time_tree_calendar_id' => $calendarId,
                'batch' => $batch,
                'events_created' => 0
            ]);
        } catch (Exception $e) {
            $this->logger->warning($e->getMessage());
        }
    }
}
