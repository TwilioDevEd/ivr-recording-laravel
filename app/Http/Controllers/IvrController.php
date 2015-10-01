<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Services_Twilio_Twiml;

class IvrController extends Controller
{
    /**
     * Responds with a welcome message with instructions
     *
     * @return \Illuminate\Http\Response
     */
    public function showWelcome()
    {
        $response = new Services_Twilio_Twiml;
        $gather = $response->gather(
            ['numDigits' => 1,
             'action' => route('main-menu', [], false),
             'method' => 'GET']
        );

        $gather->play(
            'http://howtodocs.s3.amazonaws.com/et-phone.mp3',
            ['loop' => 3]
        );

        return $response;
    }
}
