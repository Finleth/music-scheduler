<?php

namespace App\Http\Controllers;

use App\Exceptions\GenericWebFatalException;
use Exception;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AbstractController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $validationData = [];
    protected $genericErrorResponse = 'Something went wrong.';

    /**
     * @return Application|Factory|View|object
     * @throws GenericWebFatalException
     */
    public function cleanUpDataForNewRecord()
    {
        try {
            $data = ['id' => null];
            foreach ($this->validationData as $k => $v) {
                $data[$k] = null;
            }
            return (object)$data;
        } catch (Exception $e) {
            throw new GenericWebFatalException($e->getMessage());
        }
    }

    /**
     * @param $data
     * @return array
     */
    public function validAttribs($data)
    {
        $result = [];
        $validAttribs = array_keys($this->validationData);
        foreach ($data as $k => $v) {
            if (in_array($k, $validAttribs)) {
                $result[$k] = $v;
            }
        }
        // complete the form with missing attributes
        $resultKeys = array_keys($result);
        foreach ($validAttribs as $attrib) {
            if (!in_array($attrib, $resultKeys)) {
                $result[$attrib] = null;
            }
        }
        return $result;
    }
}
