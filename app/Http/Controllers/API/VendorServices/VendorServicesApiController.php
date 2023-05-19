<?php

namespace App\Http\Controllers\API\VendorServices;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VendorService;
use App\Http\Resources\VendorServices as VendorServiceResource;
use Validator;
use Auth;

class VendorServicesApiController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $services = VendorService::with(['category.parents'])->where('user_id', auth()->id())->paginate();
    
        return $this->sendResponse($services, 'Services Retrieved', 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'experience' => 'required|numeric',
            'price' => 'required|numeric',
            'home_service' => 'required|in:1,0',
            'home_service_price' => 'required_if:home_service,==,1',
            'online_consultancy' => 'required|in:1,0',
            'online_consultancy_price' => 'required_if:online_consultancy,==,1',
        ]);

        if($validate->fails())
        {
            return $this->sendErrorResponse($validate->errors()->first(), 'Something Missing..', 400);
        }
        if($request->has('file')){
            $request['banner_image'] = Helper::file_upload($request, 'file', "BannerImages");
        }
        $service = VendorService::create(array_merge($request->all(), ['user_id' => auth()->id()]));

        return $this->sendResponse($service, 'Service Added Successfully', 200);
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'experience' => 'required|numeric',
            'price' => 'required|numeric',
            'home_service' => 'required|in:1,0',
            'home_service_price' => 'required_if:home_service,==,1',
            'online_consultancy' => 'required|in:1,0',
            'online_consultancy_price' => 'required_if:online_consultancy,==,1',
        ]);

        if($validate->fails())
        {
            return $this->sendErrorResponse($validate->errors()->first(), 'Something Missing..', 400);
        }
        if($request->has('file')){
            $request['banner_image'] = Helper::file_upload($request, 'file', "BannerImages");
        }
        $service = VendorService::find($id);
        $service->update($request->all());

        return $this->sendResponse($service, 'Service Updated Successfully', 200);
    }

    public function show($id)
    {
        $service = VendorService::with(['category'])->find($id);
        if(!$service) return $this->sendErrorResponse('Service not Found', 'Invalid Service', 404);

        return $this->sendResponse($service, 'Service Retrieved', 200);
    }

    public function destroy($id)
    {
        $service = VendorService::find($id);
        if(!$service) return $this->sendErrorResponse('Service not Found', 'Invalid Service', 404);
        $service->delete();

        return $this->sendResponse('Service Removed Successfully', 'Service Retrieved', 201);
    }
}
