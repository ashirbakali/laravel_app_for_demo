<?php

namespace App\Http\Controllers\API\Country;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function getAllCountries(Request $request)
    {   $search = $request->search;
        $countries = Country::when($request->search != null, function($query) use($search){
            $query->where('name','LIKE', '%'.$search.'%');
        })->get();

        return $this->sendResponse($countries, "Fetch Countries List",200);
    }
    
    public function getCountryWiseStates(Request $request, $id)
    {   $search = $request->search;
        $states = State::where('country_id', $id)->when($request->search != null, function($query) use($search){
            $query->where('name','LIKE', '%'.$search.'%');
        })->get();

        return $this->sendResponse($states, "Fetch States List Related Country",200);
    }

    public function getStatesWiseCity(Request $request, $id)
    {   $search = $request->search;
        $cities = City::where('state_id', $id)->when($request->search != null, function($query) use($search){
            $query->where('name','LIKE', '%'.$search.'%');
        })->get();

        return $this->sendResponse($cities, "Fetch Cities List Related States",200);
    }

}