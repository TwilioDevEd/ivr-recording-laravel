<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Twilio\Twiml;

class AgentCallController extends Controller
{
    /**
     * Handles the callback after a call has finished
     *
     * @return \Illuminate\Http\Response
     */
    public function agentVoicemail(Request $request, $agentId)
    {
        $response = new Twiml();
        $callStatus = $request->input('DialCallStatus');

        if ($callStatus !== 'completed') {
            $response->say(
                'It appears that no agent is available. ' .
                'Please leave a message after the beep',
                ['voice' => 'alice', 'language' => 'en-GB']
            );

            $response->record(
                ['maxLength' => '20',
                 'method' => 'GET',
                 'action' => route('hangup', [], false),
                 'transcribeCallback' => route(
                     'store-recording', ['agent' => $agentId], false
                 )
                ]
            );

            $response->say(
                'No recording received. Goodbye',
                ['voice' => 'alice', 'language' => 'en-GB']
            );
            $response->hangup();

            return $response;
        }

        return "Ok";
    }

    /**
     * Replies with a hangup
     *
     * @return \Illuminate\Http\Response
     */
    public function showHangup()
    {
        $response = new Twiml();
        $response->say(
            'Thanks for your message. Goodbye',
            ['voice' => 'alice', 'language' => 'en-GB']
        );
        $response->hangup();

        return $response;
    }

    /**
     * Handles the callback from screening a call
     *
     * @return \Illuminate\Http\Response
     */
    public function screenCall(Request $request)
    {
        $customerPhoneNumber = $request->input('From');
        $spelledPhoneNumber = join(',', str_split($customerPhoneNumber));

        $response = new Twiml();
        $gather = $response->gather(
            ['numDigits' => 1,
             'action' => route('connect-message', [], false),
             'method' => 'GET'
            ]
        );
        $gather->say('You have an incoming call from: ');
        $gather->say($spelledPhoneNumber);
        $gather->say('Press any key to accept');

        $response->say('Sorry. Did not get your response');
        $response->hangup();

        return $response;
    }

    /**
     * Says message during connection between agent and customer (ET)
     *
     * @return \Illuminate\Http\Response
     */
    public function showConnectMessage(Request $request)
    {
        $response = new Twiml();
        $response->say('Connecting you to the extraterrestrial in distress');

        return $response;
    }
}
