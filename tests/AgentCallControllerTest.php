<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Agent;

class AgentCallControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testNewAgentVoicemail()
    {
        // When
        $response = $this->call(
            'POST',
            route('agent-voicemail', ['agent' => 1]),
            ['DialCallStatus' => 'no-answer']
        );
        $voicemail = $response->getContent();
        $voicemailResponse = new SimpleXMLElement($voicemail);

        // Then
        $this->assertEquals(2, $voicemailResponse->Say->count());
        $this->assertNotNull($voicemailResponse->Hangup);

        $recordCommand = $voicemailResponse->Record;
        $this->assertEquals('GET', $recordCommand->attributes()['method']);
        $this->assertEquals(
            route('store-recording', ['agent' => 1], false),
            $recordCommand->attributes()['transcribeCallback']
        );
        $this->assertEquals(
            route('hangup', [], false),
            $recordCommand->attributes()['action']
        );
    }

    public function testNoAgentVoicemail()
    {
        // When
        $response = $this->call(
            'POST',
            route('agent-voicemail', ['agent' => 1]),
            ['DialCallStatus' => 'completed']
        );
        // Then
        $this->assertResponseOk($response);
    }

    public function testShowHangup()
    {
        // When
        $hangupResponse = $this->call('GET', route('hangup'));
        $hangupDocument = new SimpleXMLElement($hangupResponse->getContent());

        // Then
        $this->assertNotNull($hangupDocument->Say);
        $this->assertNotEmpty($hangupDocument->Say);
        $this->assertNotNull($hangupDocument->Hangup);
    }

    public function testScreenCall()
    {
        // When
        $screenCallResponse = $this->call(
            'POST',
            route('screen-call'),
            ['From' => '+15559997777']
        );
        $screenCallDocument = new SimpleXMLElement(
            $screenCallResponse->getContent()
        );

        // Then
        $gatherCommand = $screenCallDocument->Gather;

        $this->assertEquals('1', $gatherCommand->attributes()['numDigits']);
        $this->assertEquals('GET', $gatherCommand->attributes()['method']);
        $this->assertEquals(
            route('connect-message', [], false),
            $gatherCommand->attributes()['action']
        );
    }

    public function testShowConnectMessage()
    {
        $connectMessageResponse = $this->call('GET', route('connect-message'));
        $connectMessageDocument = new SimpleXMLElement(
            $connectMessageResponse->getContent()
        );

        // Then
        $this->assertNotEmpty($connectMessageDocument->Say);
    }
}
