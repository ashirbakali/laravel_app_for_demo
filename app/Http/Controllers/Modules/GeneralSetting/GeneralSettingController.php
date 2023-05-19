<?php

namespace App\Http\Controllers\Modules\GeneralSetting;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GeneralSettingController extends Controller
{
    public function create()
    {
        $generalSetting = GeneralSetting::all()->pluck('value', 'key');

        return view('modules.Generalsetting.create',compact('generalSetting'));
    }

    public function store(Request $request)
    {
        $general = GeneralSetting::all()->pluck('value', 'key')->toArray();

        foreach($request->key as $key => $value)
        {
            if(!array_key_exists($key ,$general))
            {
                GeneralSetting::create(['key' => $key, 'value' => $value]);
            }else
            {
                $generalUpdate = GeneralSetting::where('key', $key)->first();
                $generalUpdate->value = $value;
                $generalUpdate->save();
            }
        }
        // dd($request->all());
        Session::flash('success', 'General Setting Update sucessfully');
        return redirect()->back();
    }
}
