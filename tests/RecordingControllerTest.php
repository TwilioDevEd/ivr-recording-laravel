<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Agent;
use App\Recording;

class RecordingControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testStoreRecording()
    {
        // Given
        $newAgent = new Agent(
            ['extension' => 'Alderaan',
             'phone_number' => '+15559994441']
        );
        $newAgent->save();

        // When
        $response = $this->call(
            'POST',
            route('store-recording', ['agent' => $newAgent->id]),
            [
                'Caller' => '+15558884441',
                'TranscriptionText' => 'Help me I am trapped in a phone exchange',
                'RecordingUrl' => 'http://help-pls.mp3'
            ]
        );

        // Then
        $this->assertResponseOk($response);
        $this->assertEquals('Recording saved', $response->getContent());

        $recordings = Recording::all();

        $this->assertCount(1, $recordings);
        $this->assertEquals('+15558884441', $recordings[0]->caller_number);
        $this->assertEquals('http://help-pls.mp3', $recordings[0]->recording_url);
        $this->assertEquals(
            'Help me I am trapped in a phone exchange',
            $recordings[0]->transcription
        );
        $this->assertEquals($newAgent->id, $recordings[0]->agent_id);
    }

    public function testIndex()
    {
        // Given
        $fakeNumber = '+15558884441';
        $fakeRecording = 'http://help-pls.mp3';
        $fakeTranscription = 'Help me I am trapped in a phone exchange';

        $newAgent = new Agent(
            ['extension' => 'Alderaan',
             'phone_number' => '+15559997771']
        );
        $newAgent->save();

        $newRecording = new Recording(
            ['caller_number' => $fakeNumber,
             'transcription' => $fakeTranscription,
             'recording_url' => $fakeRecording,
             'agent_id'      => $newAgent->id]
        );

        $newRecording->save();

        // When
        $response = $this->call(
            'GET',
            route('agent-recordings') . '?agentNumber=15559997771'
        );
        $responseContext = $response->getOriginalContent();

        // Then
        $this->assertCount(1, $responseContext['recordings']);
        $recordings = $responseContext['recordings'];

        $this->assertEquals($fakeNumber, $recordings[0]->caller_number);
        $this->assertEquals($fakeTranscription, $recordings[0]->transcription);
        $this->assertEquals($fakeRecording, $recordings[0]->recording_url);
        $this->assertEquals($newAgent->id, $recordings[0]->agent_id);

    }
}
