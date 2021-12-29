<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Schedule;
use App\Exceptions\GenericWebFatalException;

class ScheduleController extends AbstractController
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
            return view('schedule.list', [
                'schedule' => Schedule::paginate(config('app.pageSize'))
            ]);
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }
}
