<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Musician;
use Illuminate\Http\Request;
use App\Models\ScheduleEventType;
use Illuminate\Support\Facades\Redirect;
use App\Exceptions\GenericWebFatalException;

class MusicianController extends AbstractController
{
    protected $validationData = [
        'first_name' => 'nullable|string',
        'last_name' => 'required|string',
        'status' => 'required|string'
    ];


    /**
     * MusicianController's class constructor
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function index()
    {
        try {
            return view('musicians.list', [
                'musicians' => Musician::orderByName(config('enums.sort_direction.ASC'))
                    ->paginate(config('app.PAGE_SIZE'))
            ]);
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }

    /**
     * Display an empty form page
     *
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function new()
    {
        try {
            return view('musicians.edit', [
                'musician' => $this->cleanUpDataForNewRecord()
            ]);
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function create(Request $request)
    {
        // Validate the data request
        $request->validate($this->validationData);
        try {
            Musician::create($this->validAttribs($request->all()));

            return Redirect::route('musicians-list')
                ->with('message', 'The musician was successfully added.');
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
    public function edit($id)
    {
        try {
            $musician = Musician::where(['id' => $id])->first();
            return view('musicians.edit', [
                'musician' => $musician,
                'availableEvents' => ScheduleEventType::availableEvents($id)->get()
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
        try {
            Musician::where(['id' => $id])
                ->update($this->validAttribs($request->all()));

            return Redirect::route('musicians-list')
                ->with('message', 'The musician was successfully updated.');
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }
}
