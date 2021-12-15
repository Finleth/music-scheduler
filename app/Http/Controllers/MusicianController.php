<?php

namespace App\Http\Controllers;

use App\Exceptions\GenericWebFatalException;
use Exception;

class MusicianController extends AbstractController
{
    /**
     * @return Application|Factory|View
     * @throws GenericWebFatalException
     */
    public function index()
    {
        try {
            return view('musicians.list', []);
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }
}
