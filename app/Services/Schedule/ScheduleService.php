<?php

namespace App\Services\Schedule;

use DateTime;
use Exception;
use DateInterval;
use App\Models\Musician;
use App\Models\Schedule;
use App\Models\ScheduleEvent;
use App\Models\ScheduleEventType;
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

    /**
     * ScheduleService's class constructor
     */
    public function __construct()
    {
        $this->logger = app('log');
        $this->dayInterval = new DateInterval('P1D');
        $this->dateFormat = config('app.DATE_FORMAT');
    }

    /**
     *
     * @param string $startDate
     * @param string $endDate
     *
     * @return array
     */
    public function generateSchedule(
        string $startDate,
        string $endDate
    )
    {
        $response = [
            'success' => false,
            'error' => null
        ];

        try {
            $scheduleEventTypes = ScheduleEventType::all();
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

                        $scheduleDate = Schedule::firstOrCreate(
                            ['event_date' => $currentDate->format($this->dateFormat)]
                        );

                        // skip if event already exists
                        $eventExists = ScheduleEvent::where([
                            'schedule_id' => $scheduleDate->id,
                            'schedule_event_type_id' => $type->id
                        ])->first();

                        if (!$eventExists) {
                            $musician = $this->getMusicianToAssign($type, $currentDate);

                            if ($type && $scheduleDate && $musician) {
                                $this->createScheduleEvent($type, $scheduleDate, $musician);
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
     * @return Musician
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

            foreach ($type->musicians as $musician) {
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

                $currentSchedule = Schedule::where(['event_date' => $currentDate->format(config('app.DATE_FORMAT'))])->first();
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
}
