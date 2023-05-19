<?php

namespace App\Http\Controllers\Modules\Complain;

use App\Http\Controllers\Controller;
use App\Models\Complain;
use Illuminate\Http\Request;

class ComplainController extends Controller
{
    public function complainList()
    {
        $complains = Complain::with('user:id,name,phone,email,type')->latest()->get();
        return view('modules.Complain.index',compact('complains'));
    }

    public function status(Request $request)
    {
        Complain::find($request->id)->update($request->only('complain_status'));
        return redirect()->back();
    }
}
