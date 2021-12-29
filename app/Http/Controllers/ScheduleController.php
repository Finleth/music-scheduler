<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Exceptions\GenericWebFatalException;

class ScheduleController extends AbstractController
{
    protected $validationData = [
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start'
    ];

    /**
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function index()
    {
        try {
            return view('schedule.list', [
                'schedule' => Schedule::paginate(config('app.pageSize'))
            ]);
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }

    /**
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function generateDisplay()
    {
        try {
            return view('schedule.generate', [
                'today' => new DateTime()
            ]);
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }

    /**
     * @throws GenericWebFatalException
     */
    public function generate(Request $request)
    {
        $request->validate($this->validationData);
        try {
            return Redirect::route('schedule-list')
                ->with('message', 'The schedule was successfully generated.');
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }
}
