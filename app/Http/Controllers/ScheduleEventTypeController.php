<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use Illuminate\Http\Request;
use App\Models\ScheduleEventType;
use Illuminate\Support\Facades\Redirect;
use App\Exceptions\GenericWebFatalException;

class ScheduleEventTypeController extends AbstractController
{
    protected $validationData = [
        'title' => 'required|string',
        'time' => 'required|string',
        'day_of_week' => 'required|integer',
        'first_of_month' => 'string'
    ];


    /**
     * ScheduleEventTypeController's class constructor
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function index()
    {
        try {
            return view('schedule-event-types.list', [
                'eventTypes' => ScheduleEventType::paginate(config('app.PAGE_SIZE'))
            ]);
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }

    /**
     * Display an empty form page
     *
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function new()
    {
        try {
            return view('schedule-event-types.edit', [
                'eventType' => $this->cleanUpDataForNewRecord(),
            ]);
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function create(Request $request)
    {
        // Validate the data request
        $request->validate($this->validationData);
        try {
            $data = $request->all();
            $time = new DateTime($data['time']);

            $eventType = [
                'title' => $data['title'],
                'minute' => $time->format('i'),
                'hour' => $time->format('G'),
                'day_of_month' => '*',
                'month' => '*',
                'day_of_week' => $data['day_of_week'],
                'first_of_month' => isset($data['first_of_month'])
                    ? config('enums.YES')
                    : config('enums.NO')
            ];

            ScheduleEventType::create($eventType);

            return Redirect::route('schedule-event-types-list')
                ->with('message', 'The event type was successfully added.');
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }

    /**
     * Display a form page with values (to be edited)
     *
     * @param $id
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function edit($id)
    {
        try {
            $eventType = ScheduleEventType::where(['id' => $id])->first();
            $eventType->time = new DateTime($eventType->hour . ':' . $eventType->minute);

            return view('schedule-event-types.edit', [
                'eventType' => $eventType
            ]);
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function update(Request $request, $id)
    {
        $request->validate($this->validationData);
        try {
            $data = $request->all();
            $time = new DateTime($data['time']);

            $eventType = [
                'title' => $data['title'],
                'minute' => $time->format('i'),
                'hour' => $time->format('G'),
                'day_of_week' => $data['day_of_week'],
                'first_of_month' => isset($data['first_of_month'])
                    ? config('enums.YES')
                    : config('enums.NO')
            ];

            ScheduleEventType::where(['id' => $id])
                ->update($eventType);

            return Redirect::route('schedule-event-type-edit', $id)
                ->with('message', 'The event type was successfully updated.');
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function delete($id)
    {
        try {
            ScheduleEventType::where(['id' => $id])->delete();

            return Redirect::route('schedule-event-types-list')
                ->with('message', 'The event type was successfully deleted.');
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }
}
