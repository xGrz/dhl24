<?php

namespace xGrz\Dhl24\Http\Controllers;

class SettingsTrackingEventsController extends BaseController
{
    public function __invoke()
    {

        return view('dhl::settings.tracking-settings-index', [
            'title' => 'Tracking events'
        ]);
    }



}
