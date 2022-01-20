<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\ScheduleEvent;
use Illuminate\Support\Facades\Redirect;
use App\Exceptions\GenericWebFatalException;
use App\Services\Schedule\ScheduleTimeTreeService;

class ScheduleEventController extends AbstractController
{
    protected $validationData = [
        'musician_id' => 'required|integer'
    ];
    protected $scheduleTimeTreeService;

    /**
     *
     * ScheduleEventController's class constructor
     */
    public function __construct()
    {
        $this->middleware(['auth']);

        $this->scheduleTimeTreeService = new ScheduleTimeTreeService();
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
            $scheduleEvent = ScheduleEvent::where('id', $id)->first();

            return view('schedule-event.edit', [
                'scheduleEvent' => $scheduleEvent
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
            ScheduleEvent::where('id', $id)->update($this->validAttribs($request->all()));
            $scheduleEvent = ScheduleEvent::where('id', $id)->first();

            if ($scheduleEvent->time_tree_event_id) {
                $this->scheduleTimeTreeService->updateTimeTreeEvent($scheduleEvent);
            }
            // don't create event on TimeTree when updating if time_tree_event_id doesn't exist for now

            return Redirect::route('schedule-list', $scheduleEvent->schedule->calendar->id)
                ->with('message', 'The event was successfully updated.');
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }
}
