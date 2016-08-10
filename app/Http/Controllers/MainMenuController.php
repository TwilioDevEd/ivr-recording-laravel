<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Twilio\Twiml;

class MainMenuController extends Controller
{
    /**
     * Responds to selection of an option by the caller
     *
     * @return \Illuminate\Http\Response
     */
    public function showMenuResponse(Request $request)
    {
        $optionActions = [
            '1' => $this->_getReturnInstructions(),
            '2' => $this->_getPlanetsMenu()
        ];
        $selectedOption = $request->input('Digits');

        $actionExists = isset($optionActions[$selectedOption]);

        if ($actionExists) {
            $selectedAction = $optionActions[$selectedOption];
            return $selectedAction;

        } else {
            return redirect()->route('main-menu-redirect');
        }
    }

    /**
     * Responds with message announcing return to the main menu
     * @return Services_Twilio_Twiml
     */
    public function showMainMenuRedirect()
    {
        $errorResponse = new Twiml();
        $errorResponse->say(
            'Returning to the main menu',
            ['voice' => 'alice', 'language' => 'en-GB']
        );
        $errorResponse->redirect(route('welcome', [], false));

        return $errorResponse;
    }

    /**
     * Responds with instructions to mothership
     * @return Services_Twilio_Twiml
     */
    private function _getReturnInstructions()
    {
        $response = new Twiml();
        $response->say(
            'To get to your extraction point, get on your bike and go down the' .
            ' street. Then Left down an alley. Avoid the police cars. Turn left' .
            ' into an unfinished housing development. Fly over the roadblock. Go' .
            ' passed the moon. Soon after you will see your mother ship.',
            ['voice' => 'alice', 'language' => 'en-GB']
        );
        $response->say(
            $this->_thankYouMessage,
            ['voice' => 'alice', 'language' => 'en-GB']
        );

        $response->hangup();

        return $response;
    }

    /**
     * Responds with instructions to choose a planet
     * @return Services_Twilio_Twiml
     */
    private function _getPlanetsMenu()
    {
        $response = new Twiml();
        $gather = $response->gather(
            ['numDigits' => '1',
             'action' => route('extension-connection', [], false),
             'method' => 'GET']
        );

        $gather->say(
            'To call the planet Brodo Asogi, press 2. To call the planet' .
            ' Dagobah, press 3. To call an Oober asteroid to your location,' .
            ' press 4. To go back to the main menu, press the star key',
            ['voice' => 'alice', 'language' => 'en-GB']
        );

        return $response;
    }
}
