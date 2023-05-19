<?php

namespace App\Http\Controllers\API\ReviewRatings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\User;
use App\Models\Service;
use Auth;

class ReviewRatingAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'service_id' => 'required_if:vendor_id,null|exists:services,id',
            'vendor_id' => 'required_if:service_id,null|exists:users,id',
        ]);
        if($request->has('vendor_id'))
        {
            $user = User::with(['ratings'])->find($request->vendor_id);

            $ratings = $user->ratings;
        }else
        {
            $service = Service::with(['ratings'])->find($request->service_id);

            $ratings = $service->ratings;
        }

        return $this->sendResponse($ratings, 'Reviews and Rating', 200);
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
        $request->validate([
            'remark' => 'nullable|string',
            'rating' => 'required|lte:5|gte:1',
            'vendor_id' => 'required_if:service_id,null|exists:users,id',
            'service_id' => 'required_if:vendor_id,null|exists:services,id',
        ]);

        $rating = new Rating;

        $rating->remark = $request->remark??null;
        $rating->rating = $request->rating;
        $rating->user_id = auth()->id();
        if($request->has('vendor_id'))
        {
            $attched = User::find($request->vendor_id);
            $attched->ratings()->save($rating);

        }else
        {
            $attched = Service::find($request->service_id);
            $attched->ratings()->save($rating);
        }

        return $this->sendResponse($rating, 'Rated Succesfully', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rating = Rating::find($id);

        if(!$rating) return $this->sendError('Rating not Found', 'Invalid Id', 404);

        return $this->sendResponse($rating, 'Rated Successfully', 200);
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
        $request->validate([
            'remark' => 'nullable|string',
            'rating' => 'required|lte:5|gte:1',
        ]);

        $rating = Rating::find($id);

        if(!$rating) return $this->sendError('Rating not Found', 'Invalid Id', 404);
        $rating->update($request->only(['remark', 'rating']));

        return $this->sendResponse($rating, 'Rated Updated Successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rating = Rating::find($id);

        if(!$rating) return $this->sendError('Rating not Found', 'Invalid Id', 404);
        $rating->delete();
        return $this->sendResponse('Your Rating Deleted', 'Rated deleted Successfully', 200);
    }
}
