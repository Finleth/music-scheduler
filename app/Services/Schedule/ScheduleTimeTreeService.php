<?php

namespace App\Services\Schedule;

use DateTime;
use Exception;
use DateInterval;
use DateTimeZone;
use App\Models\ScheduleEvent;
use App\Models\ScheduleGeneration;
use Illuminate\Support\Facades\DB;
use App\Services\TimeTree\TimeTreeService;

/**
 *
 * Class ScheduleTimeTreeService
 * @package App\Services
 */
class ScheduleTimeTreeService
{
    protected $logger;
    protected $dateFormat;
    protected $scheduleEventStart;
    protected $scheduleEventEnd;
    protected $timeTreeService;

    /**
     * ScheduleTimeTreeService's class constructor
     */
    public function __construct()
    {
        $this->logger = app('log');
        $this->dateFormat = config('app.DATE_FORMAT');
        $this->timeTreeService = new TimeTreeService();
    }

    /**
     *
     * @param integer $batch
     *
     * @return array
     * @throws Exception
     */
    public function pushBatchToTimeTree(int $batch)
    {
        $response = [
            'success' => false,
            'pushed' => 0,
            'skipped' => 0
        ];

        try {
            $scheduleGeneration = ScheduleGeneration::whereBatch($batch)->first();

            DB::beginTransaction();

            foreach ($scheduleGeneration->schedule_events as $event) {
                if ($event->time_tree_event_id) {
                    $response['skipped']++;
                    continue;
                }

                $this->createTimeTreeEvent($event);
                $response['pushed']++;
            }

            DB::commit();
            $response['success'] = true;

        } catch (Exception $e) {
            DB::rollBack();
            $this->logger->warning($e->getMessage());

            throw $e;
        }

        return $response;
    }

    /**
     *
     * @param ScheduleEvent $scheduleEvent
     *
     * @return void
     * @throws Exception
     */
    public function createTimeTreeEvent(ScheduleEvent $scheduleEvent)
    {
        try {
            $this->setEventTime($scheduleEvent);

            $displayName = $scheduleEvent->musician->first_name
                ? $scheduleEvent->musician->first_name
                : $scheduleEvent->musician->last_name;

            $timeTreeEventId = $this->timeTreeService->createEvent(
                $scheduleEvent->schedule->calendar->time_tree_calendar_id,
                $scheduleEvent->scheduleEventType->title . ': ' . $displayName,
                $this->scheduleEventStart,
                $this->scheduleEventEnd,
                0
            );

            $scheduleEvent->time_tree_event_id = $timeTreeEventId;
            $scheduleEvent->save();

        } catch (Exception $e) {
            $this->logger->warning($e->getMessage());
            throw $e;
        }
    }

    /**
     *
     * @param ScheduleEvent $scheduleEvent
     *
     * @return void
     * @throws Exception
     */
    public function updateTimeTreeEvent(ScheduleEvent $scheduleEvent)
    {
        try {
            $this->setEventTime($scheduleEvent);

            $this->timeTreeService->updateEvent(
                $scheduleEvent->schedule->calendar->time_tree_calendar_id,
                $scheduleEvent->time_tree_event_id,
                $scheduleEvent->scheduleEventType->title . ': ' . $scheduleEvent->musician->first_name,
                $this->scheduleEventStart,
                $this->scheduleEventEnd,
                0
            );
        } catch (Exception $e) {
            $this->logger->warning($e->getMessage());
            throw $e;
        }
    }

    /**
     *
     * @param ScheduleEvent $scheduleEvent
     *
     * @return void
     * @throws Exception
     */
    private function setEventTime(ScheduleEvent $scheduleEvent)
    {
        try {
            $this->scheduleEventStart = new DateTime(sprintf(
                '%s %s:%s',
                $scheduleEvent->schedule->event_date->format($this->dateFormat),
                $scheduleEvent->scheduleEventType->hour,
                $scheduleEvent->scheduleEventType->minute
            ), new DateTimeZone('America/Los_Angeles'));

            $this->scheduleEventEnd = clone $this->scheduleEventStart;
            $this->scheduleEventEnd->add(new DateInterval('PT1H'));

            // convert to UTC for TimeTree
            $utcTimezone = new DateTimeZone('UTC');
            $this->scheduleEventStart->setTimezone($utcTimezone);
            $this->scheduleEventEnd->setTimezone($utcTimezone);
        } catch (Exception $e) {
            $this->logger->warning($e->getMessage());
            throw $e;
        }
    }
}
