<?php

namespace App\Http\Controllers\API\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Helpers\Helper;
use App\Models\ServiceLink;
use Carbon\Carbon;
use DB;
use Validator;
use Storage;

class ServicesApiController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $services = auth()->user()->courses()->paginate();

        return $this->sendResponse($services, 'Services Retrieved', 200);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        // dd($input);
        $validate = Validator::make($input, [
            'title' => 'required',
            'type' => 'required|in:course,session',
            'desription' => 'required',
            'charges' => 'required|numeric',
            'total_duration' => 'required|date_format:H:i:s',
            'links.*.title' => 'required',
            'links.*.link' => 'required',
            'category_id' => 'required|exists:categories,id',
        ]);

        if($validate->fails())
        {
            return $this->sendErrorResponse($validate->errors()->first(), 'Something Missing..', 400);
        }
        $input['total_duration'] = Carbon::createFromTimeString($input['total_duration']);
        DB::beginTransaction();
        try {
            if($request->has('file')){
                $input['banner_image'] = $this->file_upload($request, 'file',"BannerImages");
            }
            $service = auth()->user()->courses()->create($input);
            $input['service_id'] = $service->id;
            if($request->has('links'))
            {
                foreach($request->links as $key => $links)
                {
                    if(is_file($links['link']))
                    {
                        // dd('ss');
                        $request->service_link = $links['link'];
                        $input['path'] = Helper::file_upload($request, 'service_link', 'courses');

                        $service_links = ServiceLink::create(['service_id' => $input['service_id'], 'title' => $links['title'], 'link' => $input['path']]);
                    }else
                    {
                        $service_links = ServiceLink::create(['service_id' => $input['service_id'], 'title' => $links['title'], 'link' => $links['link']]);
                    }
                }
            }
            DB::commit();
            //code...
        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendErrorResponse($e->getMessage(), 'Something Missing..', 400);
        }


        return $this->sendResponse($service, 'Service Added Successfully', 200);
    }

    public function show($id)
    {
        $service = Service::where(['user_id' => auth()->id(), 'id' => $id])->first();

        if(!$service) return $this->sendErrorResponse('', 'Service not Found', 404);

        return $this->sendResponse($service, 'Service Retrieved', 200);
    }

    public function update($id, Request $request)
    {
        // dd($request->all());
        $input = $request->all();

        $validate = Validator::make($input, [
            'title' => 'required',
            'desription' => 'required',
            'charges' => 'required|numeric',
            'total_duration' => 'required|date_format:H:i:s',
            'links.*.title' => 'required',
            'links.*.link' => 'required',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        if($validate->fails())
        {
            return $this->sendErrorResponse($validate->errors()->first(), 'Something Missing..', 400);
        }
        DB::beginTransaction();
        try {
            $service = Service::find($id);
            // dd($service->first()->serviceLink()->get());
            $service->update($request->except(['links', '_method', 'file']));
            $input['service_id'] = $id;
            if($request->has('links'))
            {
                $service->serviceLink()->delete();

                foreach($request->links as $key => $links)
                {
                    if(is_file($links['link']))
                    {
                        // dd('ss');
                        $request->service_link = $links['link'];
                        $input['path'] = Helper::file_upload($request, 'service_link', 'courses');

                        $service_links = ServiceLink::create(['service_id' => $input['service_id'], 'title' => $links['title'], 'link' => $input['path']]);
                    }else
                    {
                        $service_links = ServiceLink::create(['service_id' => $input['service_id'], 'title' => $links['title'], 'link' => $links['link']]);
                    }
                }
            }
            if($request->has('file')){
                if(!empty($service->banner_image)){
                    $file = str_ireplace("storage/app/",'', $service->banner_image);
                    if(Storage::exists($file)){
                        Storage::delete($file);
                    }
                }
                $input['banner_image'] = Helper::file_upload($request, 'file',"BannerImages");
                $service->banner_image = $input['banner_image'];
            }
            $service->save();
            DB::commit();
            //code...
        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendErrorResponse($e->getMessage(), 'Something Missing..', 400);
        }

        return $this->sendResponse($service, 'Service Updated Successfully', 200);
    }

    public function destroy($id)
    {
        $service = Service::where(['user_id' => auth()->id(), 'id' => $id])->first();

        if(!$service) return $this->sendErrorResponse('', 'Service not Found', 404);

        $service->delete();

        return $this->sendResponse('', 'Service Deleted', 200);
    }
}
