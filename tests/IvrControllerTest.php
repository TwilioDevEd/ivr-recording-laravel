<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Agent;

class IvrControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testWelcomeResponse()
    {
        // When
        $response = $this->call('POST', route('welcome'));
        $welcomeString = $response->getContent();
        $welcomeResponse = new SimpleXMLElement($welcomeString);

        // Then
        $this->assertEquals(1, $welcomeResponse->children()->count());

        $gatherCommand = $welcomeResponse->Gather;
        $this->assertNotNull($gatherCommand);
        $this->assertEquals(1, $gatherCommand->children()->count());

        $this->assertEquals('1', $gatherCommand->attributes()['numDigits']);
        $this->assertEquals('GET', $gatherCommand->attributes()['method']);
        $this->assertEquals(
            route('main-menu', [], false), $gatherCommand->attributes()['action']
        );

        $this->assertNotNull($welcomeResponse->Gather->Play);
    }

    public function testMainMenuOptionOne()
    {
        // When
        $response = $this->call('GET', route('main-menu'), ['Digits' => 1]);
        $menuString = $response->getContent();
        $menuResponse = new SimpleXMLElement($menuString);

        // Then
        $this->assertEquals(3, $menuResponse->children()->count());
        $this->assertNotNull($menuResponse->Say);
        $this->assertEquals(2, $menuResponse->Say->count());
        $this->assertNotNull($menuResponse->Hangup);
    }

    public function testMainMenuOptionTwo()
    {
        // When
        $response = $this->call('GET', route('main-menu'), ['Digits' => 2]);
        $menuString = $response->getContent();
        $menuResponse = new SimpleXMLElement($menuString);

        // Then
        $this->assertNotNull($menuResponse->Gather);
        $this->assertNotNull($menuResponse->Gather->Say);

        $this->assertEquals(1, $menuResponse->Gather->children()->count());
        $this->assertEquals(1, $menuResponse->children()->count());
        $this->assertEquals('GET', $menuResponse->Gather->attributes()['method']);

        $this->assertEquals('1', $menuResponse->Gather->attributes()['numDigits']);
        $this->assertEquals(
            route('extension-connection', [], false),
            $menuResponse->Gather->attributes()['action']
        );
    }

    public function testNonexistentOption()
    {
        // When
        $response = $this->call('GET', route('main-menu'), ['Digits' => 99]);

        $targetResponse = $this->call('GET', $response->getTargetUrl());
        $errorResponse = new SimpleXMLElement($targetResponse->getContent());

        // Then
        $this->assertTrue($response->isRedirect(route('main-menu-redirect')));

        $this->assertEquals('Returning to the main menu', $errorResponse->Say);
        $this->assertEquals(route('welcome', [], false), $errorResponse->Redirect);
    }

    public function testCallPlanet()
    {
        // Given
        $fakePhoneNumber = '+1555999000';
        $newAgent = new Agent(
            ['extension' => 'Brodo', 'phone_number' => $fakePhoneNumber]
        );
        $newAgent->save();

        // When
        $response = $this->call('GET', route('extension-connection'), ['Digits' => 2]);
        $menuResponse = new SimpleXMLElement($response->getContent());

        // Then
        $this->assertEquals($fakePhoneNumber, $menuResponse->Dial->Number);
    }

    public function testCallUnknownPlanet()
    {
        // When
        $redirectResponse = $this->call(
            'GET', route('extension-connection'),
            ['Digits' => 99]
        );
        $response = $this->call('GET', $redirectResponse->getTargetUrl());

        $menuResponse = new SimpleXMLElement($response->getContent());

        // Then
        $this->assertTrue(
            $redirectResponse->isRedirect(route('main-menu-redirect'))
        );

        $this->assertEquals(1, $menuResponse->Say->count());
        $this->assertEquals(0, $menuResponse->Dial->count());

        $this->assertEquals(1, $menuResponse->Redirect->count());
        $this->assertEquals(route('welcome', [], false), $menuResponse->Redirect);
        $this->assertEquals('Returning to the main menu', $menuResponse->Say);
    }

    public function testStarReturnToMenu()
    {
        $this->call('GET', route('main-menu'), ['Digits' => '*']);
        $this->assertRedirectedToRoute('welcome');

        $this->call('GET', route('extension-connection'), ['Digits' => '*']);
        $this->assertRedirectedToRoute('welcome');
    }
}
