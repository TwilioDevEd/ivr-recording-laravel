<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::group(
    ['prefix' => 'ivr', 'middleware' => 'starReturn'], function () {
        Route::post(
            '/welcome', [
                'as' => 'welcome',
                'uses' => 'IvrController@showWelcome'
            ]
        );
        Route::get(
            '/main-menu', [
                'as' => 'main-menu',
                'uses' => 'MainMenuController@showMenuResponse'
            ]
        );
        Route::get(
            '/main-menu-redirect', [
                'as' => 'main-menu-redirect',
                'uses' => 'MainMenuController@showMainMenuRedirect'
            ]
        );
        Route::get(
            '/extension', [
                'as' => 'extension-connection',
                'uses' => 'ExtensionController@showExtensionConnection'
            ]
        );
        Route::post(
            '/agent/{agent}/voicemail', [
                'as' => 'agent-voicemail',
                'uses' => 'AgentCallController@agentVoicemail'
            ]
        );
        Route::post(
            '/agent/screen-call', [
                'as' => 'screen-call',
                'uses' => 'AgentCallController@screenCall'
            ]
        );
        Route::get(
            '/agent/connect-message', [
                'as' => 'connect-message',
                'uses' => 'AgentCallController@showConnectMessage'
            ]
        );
        Route::get(
            '/agent/hangup', [
                'as' => 'hangup',
                'uses' => 'AgentCallController@showHangup'
            ]
        );
        Route::post(
            '/agent/{agent}/recording', [
                'as' => 'store-recording',
                'uses' => 'RecordingController@storeRecording'
            ]
        );
    }
);
Route::get(
    '/recording', [
        'as' => 'agent-recordings',
        'uses' => 'RecordingController@indexByAgent'
    ]
);
Route::get(
    '/', function () {
        return response()->view('instructions');
    }
);
