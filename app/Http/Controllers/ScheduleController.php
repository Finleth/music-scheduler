<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use App\Models\Calendar;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Services\Schedule\ScheduleService;
use App\Exceptions\GenericWebFatalException;
use App\Models\ScheduleEventType;

class ScheduleController extends AbstractController
{
    protected $validationData = [
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start',
        'schedule_event_type_id' => 'nullable|int'
    ];
    protected $scheduleService;


    /**
     * ScheduleController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth']);

        $this->scheduleService = new ScheduleService();
    }


    /**
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function calendarList()
    {
        try {
            return view('schedule.calendars.list', [
                'calendars' => Calendar::paginate(config('app.PAGE_SIZE'))
            ]);
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }

    /**
     *
     * @param integer $id
     *
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function index(int $id)
    {
        try {
            return view('schedule.list', [
                'schedule' => Schedule::ofCalendar($id)
                    ->futureEvents()
                    ->orderBy('event_date', 'ASC')
                    ->paginate(config('app.PAGE_SIZE')),
                'calendar' => Calendar::where('id', $id)->first()
            ]);
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }

    /**
     *
     * @param integer $id
     *
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function generateDisplay(int $id)
    {
        try {
            return view('schedule.generate', [
                'today' => new DateTime(),
                'calendar' => Calendar::where('id', $id)->first(),
                'scheduleEventTypes' => ScheduleEventType::all()
            ]);
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }

    /**
     *
     * @param Request $request
     * @param integer $id
     *
     * @throws GenericWebFatalException
     */
    public function generate(Request $request, int $id)
    {
        $request->validate($this->validationData);

        try {
            $responseMessage = 'The schedule was successfully generated.';
            $responseType = 'message';
            $data = $this->validAttribs($request->all());
            $scheduleEventType = ScheduleEventType::find($data['schedule_event_type_id']);

            $response = $this->scheduleService->generateSchedule(
                $id,
                $data['start_date'],
                $data['end_date'],
                $scheduleEventType
            );

            if (!$response['success']) {
                $responseType = 'error';
                $responseMessage = 'There was an error creating the schedule.';
            }

            return Redirect::route('schedule-list', $id)
                ->with($responseType, $responseMessage);
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }
}
