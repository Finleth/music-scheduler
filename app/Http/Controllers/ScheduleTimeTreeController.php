<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Calendar;
use App\Models\ScheduleGeneration;
use App\Exceptions\GenericWebFatalException;
use App\Services\Schedule\ScheduleTimeTreeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;


class ScheduleTimeTreeController extends AbstractController
{
    protected $validationData = [
        'batch' => 'required|int'
    ];
    protected $scheduleTimeTreeService;


    /**
     * ScheduleController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth']);

        $this->scheduleTimeTreeService = new ScheduleTimeTreeService();
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
            return view('schedule.time-tree.push', [
                'scheduleGenerations' => ScheduleGeneration::ofCalendar($id)->get(),
                'calendar' => Calendar::find($id)
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
    public function create(Request $request, int $id)
    {
        $request->validate($this->validationData);

        try {
            $data = $this->validAttribs($request->all());

            $response = $this->scheduleTimeTreeService->pushBatchToTimeTree((int) $data['batch']);

            return Redirect::route('schedule-list', $id)
                ->with('message', sprintf('%s events pushed to TimeTree. %s skipped.', $response['pushed'], $response['skipped']));
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }
}
