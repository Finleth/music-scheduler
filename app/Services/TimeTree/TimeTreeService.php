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
        'all_day' => false,
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
        $this->apiService = new ApiClientRequestService();
    }

    /**
     *
     * Create a TimeTree event for the calendar
     *
     * @param string $calendarId
     * @param string $title
     * @param DateTime $start (UTC assumed)
     * @param DateTime $end   (UTC assumed)
     * @param integer $label
     *
     * @return integer|null $timeTreeEventId
     */
    public function createEvent(
        string $calendarId,
        string $title,
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
                        'all_day' => $this->defaultAttributes['all_day'],
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
                $this->url . sprintf('/calendars/%s/events', $calendarId),
                $body,
                ['Accept-Type' => $this->accept, 'Authorization' => 'Bearer ' . $this->token]
            );

            return $response->data->id ?? null;

        } catch (Exception $e) {
            $this->logger->warning($e->getMessage());
        }
    }

    /**
     *
     * Update a TimeTree event for the calendar
     *
     * @param string $calendarId,
     * @param string $eventId
     * @param string $title
     * @param DateTime $start (UTC assumed)
     * @param DateTime $end   (UTC assumed)
     * @param integer $label
     *
     * @return integer|null $timeTreeEventId
     */
    public function updateEvent(
        string $calendarId,
        string $eventId,
        string $title,
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
                        'all_day' => $this->defaultAttributes['all_day'],
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
                'PUT',
                $this->url . sprintf('/calendars/%s/events/%s', $calendarId, $eventId),
                $body,
                ['Accept-Type' => $this->accept, 'Authorization' => 'Bearer ' . $this->token]
            );

            return $response->data->id ?? null;

        } catch (Exception $e) {
            $this->logger->warning($e->getMessage());
        }
    }
}
