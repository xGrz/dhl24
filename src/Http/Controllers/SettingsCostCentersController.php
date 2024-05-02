<?php

namespace xGrz\Dhl24\Http\Controllers;

class SettingsCostCentersController extends BaseController
{
    public function __invoke()
    {
        return view('dhl::settings.costs-center-index', [
            'title' => 'Shipping costs center',
        ]);
    }


}
