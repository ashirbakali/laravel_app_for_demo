<?php

namespace App\Http\Controllers\API\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceLink;
use App\Models\Service;
use Validator;
use Log;

class ServiceLinksApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $input = $request->all();



        return $this->sendResponse('Links Added Successfully', 'Added Successfully', 200);
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
        $input = $request->all();

        $validate = Validator::make($input, [
            'title' => 'required',
            'link' => 'required',
        ]);

        if($validate->fails())
        {
            return $this->sendErrorResponse($validate->errors()->first(), 'Something Missing..', 400);
        }
        $serviceLink = ServiceLink::find($id);

        if(is_file($request->link))
        {
            // dd('ss');

            $input['path'] = Helper::file_upload($request, 'link', 'courses');
            $serviceLink->link = $input['path'];

        }else
        {
            $serviceLink->link = $input['link'];
        }
        $serviceLink->title = $input['title'];
        $serviceLink->save();

        return $this->sendResponse('Link updated Successfully', 'Updated Successfully', 200);
    }

    public function ServiceLinksupdate(Request $request, $id)
    {
        $input = $request->all();

        $validate = Validator::make($input, [
            'title' => 'required|array',
            'link' => 'required|array',
        ]);

        if($validate->fails())
        {
            return $this->sendErrorResponse($validate->errors()->first(), 'Something Missing..', 400);
        }

        $service = Service::find($id);
        if(!$service)  return $this->sendErrorResponse('Service Id is Invalid', 'Something Missing..', 400);

        $serviceLink = ServiceLink::where('service_id', $id)->delete();
        foreach($request->link as $key => $links)
        {
            if(is_file($links))
            {
                // dd('ss');
                $request->service_link = $links;
                $input['path'] = Helper::file_upload($request, 'service_link', 'courses');

                $service = ServiceLink::create(['service_id' => $request->service_id, 'title' => $request->title[$key], 'link' => $input['path']]);
            }else
            {
                $service = ServiceLink::create(['service_id' => $request->service_id, 'title' => $request->title[$key], 'link' => $links]);
            }
        }

        return $this->sendResponse('Links updated Successfully', 'Updated Successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $serviceLink = ServiceLink::find($id);
        if(!$serviceLink)
        {
            return $this->sendErrorResponse('Links not Deleted', 'Something went wrong....', 400);
        }
        try
        {
            if(!empty($serviceLink->link)){
                $file = str_ireplace("storage/app/",'', $serviceLink->link);
                if(Storage::exists($file)){
                    Storage::delete($file);
                }
            }

        }catch(\Exception $e)
        {
            Log::debug("Service Link File Delete Error");
            Log::debug($e->getMessage());
        }
        $serviceLink->delete();

        return $this->sendResponse('Links Deleted Successfully', 'Deleted Successfully', 200);
    }
}
