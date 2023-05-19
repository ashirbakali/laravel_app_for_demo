<?php


namespace App\Http\Controllers\Modules\Vendors;


use App\Http\Controllers\AuthenticatedController;
use Illuminate\Http\Request;

class VendorsController extends AuthenticatedController
{
    public function index()
    {
        return view('dashboard');
    }

    public function create(Request $request){
        dd($request->all());
        $logic = new VendorsLogic();
        $logic->subscriptionLogic();
        return view('dashboard');
    }

    public function update(Request $request){
        $logic = new VendorsLogic();
        $logic->subscriptionLogic();
        return view('dashboard');
    }


}
