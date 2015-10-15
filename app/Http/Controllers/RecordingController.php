<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Recording;
use App\Agent;

class RecordingController extends Controller
{

    /**
     * Shows an index of existings recordings
     *
     * @return \Illuminate\Http\Response
     */
    public function indexByAgent(Request $request)
    {
        $agentNumber = $request->input('agentNumber');
        $agentNumberInE164Format = '+' . $agentNumber;

        $agent = Agent::where(['phone_number' => $agentNumberInE164Format])
               ->firstOrFail();
        $allRecordings = Recording::where(['agent_id' => $agent->id])->get();

        return response()->view(
            'recordings.index',
            ['recordings' => $allRecordings,
             'agent' => $agent]
        );
    }

    /**
     * Store a new recording from callback
     *
     * @return \Illuminate\Http\Response
     */
    public function storeRecording(Request $request, $agentId)
    {
        $newRecording = new Recording(
            ['caller_number' => $request->input('Caller'),
             'transcription' => $request->input('TranscriptionText'),
             'recording_url' => $request->input('RecordingUrl'),
             'agent_id'      => $agentId]
        );

        $newRecording->save();

        return "Recording saved";
    }
}
