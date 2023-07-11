<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Agent;
use Twilio\Twiml;

class ExtensionController extends Controller
{
    /**
     * Responds with a <Dial> to the caller's planet
     *
     * @return \Illuminate\Http\Response
     */
    public function showExtensionConnection(Request $request)
    {
        $selectedOption = $request->input('Digits');

        try {
            $agent = $this->_getAgentForDigit($selectedOption);
        }
        catch (ModelNotFoundException $e){
            return redirect()->route('main-menu-redirect');
        }

        $numberToDial = $agent->phone_number;

        $response = new Twiml();
        $response->say(
            "You'll be connected shortly to your planet. " .
            $this->_thankYouMessage,
            ['voice' => 'Polly.Amy', 'language' => 'en-GB']
        );

        $dialCommand = $response->dial(
            ['action' => route('agent-voicemail', ['agent' => $agent->id], false),
             'method' => 'POST']
        );
        $dialCommand->number(
            $numberToDial,
            ['url' => route('screen-call', [], false)]
        );

        return $response;
    }

    private function _getAgentForDigit($digit)
    {
        $planetExtensions = [
            '2' => 'Brodo',
            '3' => 'Dagobah',
            '4' => 'Oober'
        ];
        $planetExtensionExists = isset($planetExtensions[$digit]);

        if ($planetExtensionExists) {
            $agent = Agent::where(
                'extension', '=', $planetExtensions[$digit]
            )->firstOrFail();

            return $agent;
        } else {
            throw new ModelNotFoundException;
        }
    }
}
