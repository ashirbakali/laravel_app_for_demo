<?php

namespace App\Http\Controllers\API\GeneralSetting;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;

class GeneralSettingController extends Controller
{
    public function getGeneralSetting($key)
    {
        $privacy = GeneralSetting::where('key', $key)->pluck('value', 'key');
        // dd($privacy);
        if(!count($privacy))
        {
            $privacy = [$key => ''];
        }

        return $this->sendResponse($privacy, 'General data retrive', 200);
    }
}
