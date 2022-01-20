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
use App\Models\ScheduleGeneration;

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
     * @param Request $request
     * @param integer $id
     *
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function index(Request $request, int $id)
    {
        try {
            $data = $request->except('page');
            $start = new DateTime();
            $end = null;

            if (array_key_exists('start', $data)) {
                if ($data['start'] === null) {
                    $start = null;

                    // Update null to an empty string for Eloquent pagination
                    $data['start'] = '';
                } else {
                    $start = new DateTime($data['start']);
                }
            }

            if (array_key_exists('end', $data)) {
                $end = $data['end'] !== null ? new DateTime($data['end']) : null;
            }

            return view('schedule.list', [
                'schedule' => Schedule::ofCalendar($id)
                    ->ofBatch($data['batch'] ?? null)
                    ->eventDateBetween($start, $end)
                    ->orderBy('event_date', 'ASC')
                    ->paginate(config('app.PAGE_SIZE'))
                    ->appends($data),
                'scheduleGenerations' => ScheduleGeneration::ofCalendar($id)->get(),
                'calendar' => Calendar::find($id)
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
                'calendar' => Calendar::find($id),
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

            return Redirect::route('schedule-list', ['id' => $id, 'start' => '', 'batch' => $response['batch']])
                ->with($responseType, $responseMessage);
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }
}
