<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Recording;

class RecordingController extends Controller
{
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
