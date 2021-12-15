<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class GenericWebFatalException extends Exception
{

    public function render()
    {
        Log::error('Something went wrong. ' . $this->getMessage());
        return view('pages.error.500');
    }

}
