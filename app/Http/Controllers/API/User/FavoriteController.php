<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Models\FavoriteDoctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fav_doc = FavoriteDoctor::where([['user_id', Auth::id()]])->get();
        return $this->sendResponse($fav_doc,"User Favorite Doctor list fetched",200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'doctor_id' => [
                'required',
                Rule::exists('users', 'id')
                    ->where('type', 'CLIENT'),
            ],
        ]);
        if ($validate->fails()) {
            return $this->sendErrorResponse($validate->errors()->first(), 'Something Missing..', 400);
        }
        $request['user_id'] = Auth::id();
        $favorite_doctor = FavoriteDoctor::where([['user_id', $request->user_id], ['doctor_id', $request->doctor_id]]);
        if ($favorite_doctor->count()>0) {
            $favorite_doctor->delete();
            $msg = "Doctor removed from favorite list";
        }else{
            $favorite_doctor = FavoriteDoctor::create($request->only('user_id', 'doctor_id'));   
            $msg = "Doctor added into favorite list";
        }
        
        return $this->sendResponse([],$msg,200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
