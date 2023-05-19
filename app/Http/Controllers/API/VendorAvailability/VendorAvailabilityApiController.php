<?php

namespace App\Http\Controllers\API\VendorAvailability;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DoctorAvailability;
use App\Models\User;
use Carbon\CarbonInterval;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VendorAvailabilityApiController extends Controller
{
    function __construct()
    {
        $this->middleware(['auth:api', 'checkuser']);
    }

    public function index()
    {
        $availability = DoctorAvailability::where('user_id', auth()->id())->get()->toArray();

        return $this->sendResponse($availability, 'Availability', 200);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        DB::beginTransaction();
        try {
            $validate = Validator::make($input, [
                'day.*' => 'required|in:MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY,SUNDAY',
                'start_time.*' => 'required|date_format:H:i:s',
                'end_time.*' => 'required|date_format:H:i:s',
                'type.*' => 'required|in:hour,minute',
                'gap_count.*' => 'required|numeric',
            ]);

            if ($validate->fails()) {
                return $this->sendErrorResponse($validate->errors()->first(), 'Something Missing', 400);
            }
            foreach ($input['day'] as $key => $day) {
                $availability = DoctorAvailability::where([['user_id', auth()->id()], ['day', $day]])->first();
                if ($availability) {
                    // dd($input['status'][$key]);
                    $availability->update([
                        'day'  => $input['day'][$key],
                        'start_time'  => $input['start_time'][$key],
                        'end_time'  => $input['end_time'][$key],
                        'type'  => $input['type'][$key],
                        'gap_count'  => $input['gap_count'][$key],
                        'status'  => $input['status'][$key],
                    ]);
                } else {
                    $availability = User::find(auth()->id())->availability()->create([
                        'day'  => $input['day'][$key],
                        'start_time'  => $input['start_time'][$key],
                        'end_time'  => $input['end_time'][$key],
                        'type'  => $input['type'][$key],
                        'gap_count'  => $input['gap_count'][$key],
                        'status'  => $input['status'][$key],
                    ]);
                }
                $availability->slots()->delete();
                if ($availability->status == 1) {
                    $gap_count = $availability->type == "hour" ? $availability->gap_count * 60 : $availability->gap_count;
                    $intervals = CarbonInterval::minutes($gap_count)->toPeriod($availability->start_time, $availability->end_time);
                    $stater = "";
                    $keys = 0;
                    $slots = array();
                    foreach ($intervals as $key1 => $date) {
                        if ($key1 == 0) {
                            $stater = $date->format('H:i');
                        } else {
                            $slots[$keys]['start_time'] = $stater;
                            $slots[$keys]['end_time'] = $date->format('H:i');
                            $stater = $date->format('H:i');
                            $keys++;
                        }
                    }
                    foreach ($slots as $slot) {
                        $availability->slots()->create([
                            'start_time' => $slot['start_time'],
                            'end_time' => $slot['end_time'],
                        ]);
                    }
                }
            }
            DB::commit();
            return $this->sendResponse(auth()->user()->availability, 'Availability added Successfully', 200);
        } catch (Exception $e) {
            DB::rollback();
            return $this->sendErrorResponse($e->getMessage(), "Something went wrong", 500);
        }
    }

    public function show($id)
    {
        $availability = DoctorAvailability::where(['user_id' => auth()->id(), 'id' => $id])->first();

        return $this->sendResponse($availability, 'Availability', 200);
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validate = Validator::make($input, [
            'day' => 'required|in:monaday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s',
        ]);

        if ($validate->fails()) {
            return $this->sendErrorResponse($validate->errors()->first(), 'Something Missing', 400);
        }

        $availability = DoctorAvailability::update($id, $input);

        $availability = DoctorAvailability::find($id);

        return $this->sendResponse($availability, 'Availability Updated Successfully', 200);
    }

    public function destroy($id)
    {
        $availability = DoctorAvailability::find($id);

        return $this->sendResponse($availability->delete(), 'Availability Deleted Successfully', 200);
    }
}
