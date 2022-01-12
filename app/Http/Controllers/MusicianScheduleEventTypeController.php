<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\ScheduleEventType;
use Illuminate\Support\Facades\Redirect;
use App\Models\MusicianScheduleEventType;
use App\Exceptions\GenericWebFatalException;

class MusicianScheduleEventTypeController extends AbstractController
{
    protected $validationData = [
        'schedule_event_type_id' => 'required|integer',
        'frequency' => 'required|integer'
    ];


    /**
     * MusicianScheduleEventTypeController's class constructor
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display an empty form page
     *
     * @param $id
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function new($id)
    {
        try {
            return view('musicians.schedule-event-types.edit', [
                'scheduleEventType' => $this->cleanUpDataForNewRecord(),
                'musicianId' => $id,
                'scheduleEventTypes' => ScheduleEventType::availableEvents($id)->get()
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
    public function create(Request $request, $id)
    {
        // Validate the data request
        $request->validate($this->validationData);
        try {
            $data = $this->validAttribs($request->all());
            $data['musician_id'] = $id;

            MusicianScheduleEventType::create($data);

            return Redirect::route('musician-edit', $id)
                ->with('message', 'The event was successfully added.');
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }

    /**
     * Display a form page with values (to be edited)
     *
     * @param $musicianId
     * @param $eventId
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function edit($musicianId, $eventId)
    {
        try {
            $scheduleEventType = MusicianScheduleEventType::where(['id' => $eventId])->first();

            return view('musicians.schedule-event-types.edit', [
                'musicianId' => $musicianId,
                'scheduleEventType' => $scheduleEventType,
                'scheduleEventTypes' => ScheduleEventType::availableEvents($musicianId, $scheduleEventType->schedule_event_type_id)->get()
            ]);
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param $musicianId
     * @param $eventId
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function update(Request $request, $musicianId, $eventId)
    {
        try {
            MusicianScheduleEventType::where(['id' => $eventId])
                ->update($this->validAttribs($request->all()));

            return Redirect::route('musician-edit', $musicianId)
                ->with('message', 'The event was successfully updated.');
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }

    /**
     * @param $musicianId
     * @param $eventId
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function delete($musicianId, $eventId)
    {
        try {
            MusicianScheduleEventType::where(['id' => $eventId])->forceDelete();

            return Redirect::route('musician-edit', $musicianId)
                ->with('message', 'The event was successfully deleted.');
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }
}
