<?php

namespace App\Services\Schedule;

use DateTime;
use Exception;
use DateInterval;
use DateTimeZone;
use App\Models\Musician;
use App\Models\Schedule;
use App\Models\ScheduleEvent;
use App\Models\ScheduleEventType;
use Illuminate\Support\Facades\DB;
use App\Services\TimeTree\TimeTreeService;

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
        $this->timeTreeService = new TimeTreeService();
    }

    /**
     *
     * @param integer $id
     * @param string $startDate
     * @param string $endDate
     * @param ScheduleEventType $scheduleEventType
     *
     * @return array
     */
    public function generateSchedule(
        int $id,
        string $startDate,
        string $endDate,
        ScheduleEventType $scheduleEventType = null
    )
    {
        $response = [
            'success' => false,
            'error' => null
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

            while ($currentDate->format($this->dateFormat) <= $endDate->format($this->dateFormat)) {
                foreach ($scheduleEventTypes as $type) {
                    if ($type->day_of_week === $currentDate->format('w')) {
                        // skip if current date isn't first of the month
                        // and the event type requires it
                        if ($type->first_of_month === config('enums.YES')
                            && (int) $currentDate->format('j') > 7) {
                            continue;
                        }

                        $scheduleDate = Schedule::firstOrCreate([
                            'time_tree_calendar_id' => $id,
                            'event_date' => $currentDate->format($this->dateFormat)
                        ]);

                        // skip if event already exists
                        $eventExists = ScheduleEvent::where([
                            'schedule_id' => $scheduleDate->id,
                            'schedule_event_type_id' => $type->id
                        ])->first();

                        if (!$eventExists) {
                            $musician = $this->getMusicianToAssign($type, $currentDate);

                            if ($type && $scheduleDate && $musician) {
                                $scheduleEvent = $this->createScheduleEvent($type, $scheduleDate, $musician);

                                // push event to TimeTree
                                if ($scheduleEvent) {
                                    $this->createTimeTreeEvent($scheduleEvent);
                                } else {
                                    $this->logger->warning(sprintf(
                                        'Error creating schedule event for %s on %s',
                                        $type->title,
                                        $currentDate->format($this->dateFormat)
                                    ));
                                }
                            } else {
                                $this->logger->warning(sprintf(
                                    'Unable to create schedule event for %s on %s',
                                    $type->title,
                                    $currentDate->format($this->dateFormat)
                                ));
                            }
                        }
                    }
                }

                $currentDate->add($this->dayInterval);
            }

            DB::commit();
            $response['success'] = true;

        } catch (Exception $e) {
            DB::rollback();
            $this->logger->warning($e->getMessage());
        }

        return $response;
    }

    /**
     *
     * @param ScheduleEventType $type
     * @param DateTime $currentDate
     *
     * @return Musician|null
     */
    private function getMusicianToAssign(
        ScheduleEventType $type,
        DateTime $currentDate
    )
    {
        try {
            $musicianWeights = [];
            $pickedMusician = [
                'musician' => null,
                'weight' => 0
            ];
            $musicians = $type->musicians()->available($currentDate)->get();

            foreach ($musicians as $musician) {
                // select musician if the have a forced assigned for that week
                if ($musician->pivot->schedule_week === ceil((float) $currentDate->format('j') / 7)) {
                    return $musician;
                }

                // skip any musicians not set to be automatically scheduled
                if ($musician->pivot->auto_schedule === config('enums.NO')) {
                    continue;
                }

                $scheduleEvent = ScheduleEvent::mostRecentTypeForMusician($musician->id, $type->id)->first();

                $frequency = $musician->pivot->frequency / 100;

                if ($scheduleEvent) {
                    $dateDiff = $currentDate->diff($scheduleEvent->schedule->event_date);
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

                $currentSchedule = Schedule::where(['event_date' => $currentDate->format($this->dateFormat)])->first();

                // if musician already scheduled for a different event that day, lower their priority
                if ($currentSchedule) {
                    $sameDayEvent = $musician->schedule_events()->where([
                        'schedule_id' => $currentSchedule->id
                    ])->first();

                    if ($sameDayEvent) {
                        $musicianWeights[$musician->id]['weight'] /= $this->defaultMultiplier;
                    }
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
     *
     * @return ScheduleEvent
     */
    private function createScheduleEvent(
        ScheduleEventType $type,
        Schedule $scheduleDate,
        Musician $musician
    )
    {
        try {
            return ScheduleEvent::firstOrCreate([
                'schedule_event_type_id' => $type->id,
                'schedule_id' => $scheduleDate->id
            ], ['musician_id' => $musician->id]);
        } catch (Exception $e) {
            $this->logger->warning($e->getMessage());
        }
    }

    /**
     *
     * @param ScheduleEvent $scheduleEvent
     *
     * @return void
     */
    public function createTimeTreeEvent(ScheduleEvent $scheduleEvent)
    {
        try {
            $this->setEventTime($scheduleEvent);

            $timeTreeEventId = $this->timeTreeService->createEvent(
                $scheduleEvent->schedule->calendar->time_tree_calendar_id,
                $scheduleEvent->schedule_event_type->title . ': ' . $scheduleEvent->musician->first_name,
                $this->scheduleEventStart,
                $this->scheduleEventEnd,
                0
            );

            $scheduleEvent->time_tree_event_id = $timeTreeEventId;
            $scheduleEvent->save();

        } catch (Exception $e) {
            $this->logger->warning($e->getMessage());
        }
    }

    /**
     *
     * @param ScheduleEvent $scheduleEvent
     *
     * @return void
     */
    public function updateTimeTreeEvent(ScheduleEvent $scheduleEvent)
    {
        try {
            $this->setEventTime($scheduleEvent);

            $this->timeTreeService->updateEvent(
                $scheduleEvent->schedule->calendar->time_tree_calendar_id,
                $scheduleEvent->time_tree_event_id,
                $scheduleEvent->schedule_event_type->title . ': ' . $scheduleEvent->musician->first_name,
                $this->scheduleEventStart,
                $this->scheduleEventEnd,
                0
            );
        } catch (Exception $e) {
            $this->logger->warning($e->getMessage());
        }
    }

    /**
     *
     * @param ScheduleEvent $scheduleEvent
     *
     * @return void
     */
    private function setEventTime(ScheduleEvent $scheduleEvent)
    {
        $this->scheduleEventStart = new DateTime(sprintf(
            '%s %s:%s',
            $scheduleEvent->schedule->event_date->format($this->dateFormat),
            $scheduleEvent->schedule_event_type->hour,
            $scheduleEvent->schedule_event_type->minute
        ), new DateTimeZone('America/Los_Angeles'));
        $this->scheduleEventEnd = clone $this->scheduleEventStart;
        $this->scheduleEventEnd->add(new DateInterval('PT1H'));

        // convert to UTC for time tree
        $utcTimezone = new DateTimeZone('UTC');
        $this->scheduleEventStart->setTimezone($utcTimezone);
        $this->scheduleEventEnd->setTimezone($utcTimezone);
    }
}
