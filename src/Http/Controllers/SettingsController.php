<?php

namespace xGrz\Dhl24\Http\Controllers;

class SettingsController extends BaseController
{
    public function __invoke()
    {
        return view('dhl::settings.index', [
            'title' => 'Settings',
        ]);
    }


}
