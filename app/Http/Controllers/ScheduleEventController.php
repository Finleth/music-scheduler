<?php

namespace App\Http\Controllers;

use Exception;
use App\Exceptions\GenericWebFatalException;
use App\Models\Musician;
use App\Models\ScheduleEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ScheduleEventController extends AbstractController
{
    protected $validationData = [
        'musician_id' => 'required|integer'
    ];

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
            $scheduleEvent = ScheduleEvent::where(['id' => $id])->first();

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
            ScheduleEvent::where(['id' => $id])
                ->update($this->validAttribs($request->all()));

            return Redirect::route('schedule-list')
                ->with('message', 'The event was successfully updated.');
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }
}
