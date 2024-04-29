<?php

namespace xGrz\Dhl24\Http\Controllers;

use xGrz\Dhl24\Models\DHLContentSuggestion;
use xGrz\Dhl24\Models\DHLCostCenter;

class SettingsController extends BaseController
{
    public function index()
    {
        return view('dhl::settings.index', [
            'title' => 'Settings',
            'costCenters' => DHLCostCenter::orderBy('is_default', 'desc')->orderBy('name')->get(),
            'contents' => DHLContentSuggestion::orderBy('content')->get()
        ]);
    }


}
