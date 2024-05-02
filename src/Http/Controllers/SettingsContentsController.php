<?php

namespace xGrz\Dhl24\Http\Controllers;

class SettingsContentsController extends BaseController
{
    public function __invoke()
    {
        return view('dhl::settings.contents-index', [
            'title' => 'Shipping contents'
        ]);
    }



}
