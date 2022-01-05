<?php

namespace App\Http\Controllers;

use Exception;
use App\Exceptions\GenericWebFatalException;
use App\Models\ScheduleEvent;
use App\Services\Schedule\ScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ScheduleEventController extends AbstractController
{
    protected $validationData = [
        'musician_id' => 'required|integer'
    ];
    protected $scheduleService;

    /**
     *
     * ScheduleEventController's class constructor
     */
    public function __construct()
    {
        $this->scheduleService = new ScheduleService();
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
                $this->scheduleService->updateTimeTreeEvent($scheduleEvent);
            } // don't create event on TimeTree when updating if time_tree_event_id doesn't exist for now

            return Redirect::route('schedule-list')
                ->with('message', 'The event was successfully updated.');
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }
}
