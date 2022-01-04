<?php

namespace App\Services\TimeTree;

use DateTime;
use Exception;
use App\Services\RestApi\ApiClientRequestService;

/**
 *
 * Class TimeTreeService
 * @package App\Services
 */
class TimeTreeService
{
    protected $logger;
    protected $url = 'https://timetreeapis.com';
    protected $accept = 'application/vnd.timetree.v1+json';
    protected $defaultAttributes = [
        'category' => 'schedule',
        'labelType' => 'label',
        'timezone' => 'America/Los_Angeles'
    ];
    protected $labels = ['1', '2', '5', '6'];
    protected $token;
    protected $calendarId;

    protected $apiService;

    /**
     *
     * TimeTreeService's class constructor
     */
    public function __construct()
    {
        $this->logger = app('log');
        $this->token = config('services.time_tree.TOKEN');
        $this->calendarId = config('services.time_tree.CALENDAR_ID');
        $this->apiService = new ApiClientRequestService();
    }

    /**
     *
     * Create a TimeTree event for the calendar
     *
     * @param string $title
     * @param boolean $allDay
     * @param DateTime $start (UTC assumed)
     * @param DateTime $end   (UTC assumed)
     * @param integer $label
     *
     * @return integer|null $timeTreeEventId
     */
    public function createEvent(
        string $title,
        bool $allDay,
        DateTime $start,
        DateTime $end,
        int $label
    )
    {
        try {
            $body = [
                'data' => [
                    'attributes' => [
                        'category' => $this->defaultAttributes['category'],
                        'title' => $title,
                        'all_day' => $allDay,
                        'start_at' => $start->format(config('app.ISO_8601_DATE_FORMAT')),
                        'end_at' => $end->format(config('app.ISO_8601_DATE_FORMAT')),
                        'start_timezone' => $this->defaultAttributes['timezone'],
                        'end_timezone' => $this->defaultAttributes['timezone']
                    ],
                    'relationships' => [
                        'label' => [
                            'data' => [
                                'id' => $this->labels[$label],
                                'type' => $this->defaultAttributes['labelType']
                            ]
                        ]
                    ]
                ]
            ];

            $response = $this->apiService->send(
                'POST',
                $this->url . sprintf('/calendars/%s/events', $this->calendarId),
                $body,
                [
                    'Accept-Type' => $this->accept,
                    'Authorization' => 'Bearer ' . $this->token
                ]
            );

            return $response->data->id ?? null;

        } catch (Exception $e) {
            $this->logger->warning($e->getMessage());
        }
    }
}
