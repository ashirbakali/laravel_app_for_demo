<?php

namespace App\Http\Controllers\API\Complain;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplainController extends Controller
{
    public function addComplainDetail(Request $request)
    {
        $complain = User::find(Auth::id())->complains()->create($request->only('message'));
        return $this->sendResponse($complain, 'Complain successfully register', 200);
    }

    public function complainList()
    {
        return $this->sendResponse(Auth::user()->complains, "Complain List Retrive",200);
    }
}
