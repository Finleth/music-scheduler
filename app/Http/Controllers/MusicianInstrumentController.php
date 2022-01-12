<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\MusicianInstrument;
use App\Exceptions\GenericWebFatalException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class MusicianInstrumentController extends AbstractController
{
    protected $validationData = [
        'name' => 'required|string',
        'primary' => 'required|string'
    ];


    /**
     * MusicianInstrumentController's class constructor
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
            return view('musicians.instruments.edit', [
                'instrument' => $this->cleanUpDataForNewRecord(),
                'musicianId' => $id,
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

            MusicianInstrument::create($data);

            return Redirect::route('musician-edit', $id)
                ->with('message', 'The instrument was successfully added.');
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
    public function edit($musicianId, $instrumentId)
    {
        try {
            $instrument = MusicianInstrument::where(['id' => $instrumentId])->first();

            return view('musicians.instruments.edit', [
                'musicianId' => $musicianId,
                'instrument' => $instrument
            ]);
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param $musicianId
     * @param $instrumentId
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function update(Request $request, $musicianId, $instrumentId)
    {
        try {
            MusicianInstrument::where(['id' => $instrumentId])
                ->update($this->validAttribs($request->all()));

            return Redirect::route('musician-edit', $musicianId)
                ->with('message', 'The instrument was successfully updated.');
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }

    /**
     * @param $musicianId
     * @param $instrumentId
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function delete($musicianId, $instrumentId)
    {
        try {
            MusicianInstrument::where(['id' => $instrumentId])->delete();

            return Redirect::route('musician-edit', $musicianId)
                ->with('message', 'The instrument was successfully deleted.');
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }
}
