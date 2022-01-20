<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Calendar;
use Illuminate\Http\Request;
use App\Models\ScheduleGeneration;
use Illuminate\Support\Facades\Redirect;
use App\Services\Schedule\ScheduleService;
use App\Exceptions\GenericWebFatalException;


class ScheduleTimeTreeController extends AbstractController
{
    protected $validationData = [
        'batch' => 'required|int'
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
            // use new ScheduleTimeTreeService to get schedule events by batch
            // and create events on time tree using TimeTreeService
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }
}
