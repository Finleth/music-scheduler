<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use App\Exceptions\GenericWebFatalException;
use App\Models\Schedule;
use App\Services\Schedule\ScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ScheduleController extends AbstractController
{
    protected $validationData = [
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start'
    ];
    protected $scheduleService;

    /**
     * ScheduleController constructor.
     */
    public function __construct()
    {
        $this->scheduleService = new ScheduleService();
    }


    /**
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function index()
    {
        try {
            return view('schedule.list', [
                'schedule' => Schedule::futureEvents()
                    ->orderBy('event_date', 'ASC')
                    ->paginate(config('app.PAGE_SIZE'))
            ]);
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }

    /**
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function generateDisplay()
    {
        try {
            return view('schedule.generate', [
                'today' => new DateTime()
            ]);
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }

    /**
     * @throws GenericWebFatalException
     */
    public function generate(Request $request)
    {
        $request->validate($this->validationData);

        try {
            $responseMessage = 'The schedule was successfully generated.';
            $responseType = 'message';
            $data = $this->validAttribs($request->all());

            $response = $this->scheduleService->generateSchedule(
                $data['start_date'],
                $data['end_date']
            );

            if (!$response['success']) {
                $responseType = 'error';
                $responseMessage = 'There was an error creating the schedule.';
            }

            return Redirect::route('schedule-list')
                ->with($responseType, $responseMessage);
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }
}
