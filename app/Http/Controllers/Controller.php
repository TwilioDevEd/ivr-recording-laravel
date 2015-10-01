<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    public function __construct()
    {
        $this->_thankYouMessage = 'Thank you for calling the ET Phone Home' .
                                  ' Service - the adventurous alien\'s first choice' .
                                  ' in intergalactic travel.';
    }

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
