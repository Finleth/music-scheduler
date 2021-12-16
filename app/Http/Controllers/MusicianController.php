<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Musician;
use App\Exceptions\GenericWebFatalException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class MusicianController extends AbstractController
{
    protected $validationData = [
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'status' => 'required|string'
    ];

    /**
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function index()
    {
        try {
            return view('musicians.list', [
                'musicians' => Musician::paginate(config('app.pageSize'))
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
                'musician' => $musician
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
