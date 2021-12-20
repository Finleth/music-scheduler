<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use App\Models\MusicianBlackout;
use App\Exceptions\GenericWebFatalException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class MusicianBlackoutController extends AbstractController
{
    protected $validationData = [
        'start' => 'required|date',
        'end' => 'required|date|after_or_equal:start'
    ];

    /**
     * Display an empty form page
     *
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function new()
    {
        try {
            return view('musicians.blackouts.edit', [
                'blackout' => $this->cleanUpDataForNewRecord()
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
            $data['start'] = strtotime($data['start']);
            $data['end'] = strtotime($data['end']);

            MusicianBlackout::create($data);

            return Redirect::route('musician-edit', $id)
                ->with('message', 'The blackout was successfully added.');
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
    public function edit($musicianId, $blackoutId)
    {
        try {
            $blackout = MusicianBlackout::where(['id' => $blackoutId])->first();

            return view('musicians.blackouts.edit', [
                'blackout' => $blackout
            ]);
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param $musicianId
     * @param $blackoutId
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function update(Request $request, $musicianId, $blackoutId)
    {
        $request->validate($this->validationData);
        try {
            $data = $this->validAttribs($request->all());
            $start = new DateTime($data['start']);
            $data['start'] = $start;
            $end = new DateTime($data['end']);
            $data['end'] = $end;

            MusicianBlackout::where(['id' => $blackoutId])
                ->update($data);

            return Redirect::route('musician-edit', $musicianId)
                ->with('message', 'The blackout was successfully updated.');
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }

    /**
     * @param $musicianId
     * @param $blackoutId
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function delete($musicianId, $blackoutId)
    {
        try {
            MusicianBlackout::where(['id' => $blackoutId])->delete();

            return Redirect::route('musician-edit', $musicianId)
                ->with('message', 'The blackout was successfully deleted.');
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }
}
