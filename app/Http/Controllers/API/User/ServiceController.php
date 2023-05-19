<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Card;
use App\Models\DoctorAvailability;
use App\Models\Service;
use App\Models\TimeSlot;
use App\Models\User;
use App\Models\VendorService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function topServices(Request $request)
    {
        $service = VendorService::with('user', 'category')->where('status', 1)->orderBy('id', 'desc')->paginate($request->limit ?? 5);
        return $this->sendResponse($service, "Fetch top services list");
    }


    public function onlineSessionList(Request $request)
    {
        $sessions = Service::with('user', 'category')->where([['type', 'session'], ['status', 1]])->orderBy('id', 'desc')->paginate($request->limit ?? 5);
        return $this->sendResponse($sessions, "Fetch Online session list");
    }

    public function searchClient(Request $request)
    {
        $search = $request->search;
        $category_id = $request->category_id;
        $sessions = User::when($search, function ($query) use ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        })
            ->whereHas('Addedservices', function ($query2) use ($category_id) {
                $query2->where('category_id', $category_id);
            })
            ->orderBy('id', 'desc')->paginate($request->limit ?? 5);
        return $this->sendResponse($sessions, "Fetch Online session list");
    }

    public function getUserDetailWithCourses($id)
    {
        $user = User::find($id);

        $user->setAppends([]);
        $user['course'] = $user->courses()->where([['type', 'course'], ['status', 1]])->get();
        return $this->sendResponse($user, "Fetch Online session list");
    }

    public function getVendorDateWiseTimeSlots(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'vendor_id' => [
                'required',
                Rule::exists('users', 'id')
                    ->where('type', 'CLIENT'),
            ],
            'day' => 'required|in:MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY,SUNDAY',
            'appointment_datetime' => 'required|date_format:Y-m-d',
        ]);

        if ($validate->fails()) {
            return $this->sendErrorResponse($validate->errors()->first(), 'Something Missing..', 400);
        }

        $appointment_timeslots = Appointment::select('time_slot_id')->where([['appointment_datetime', $request->appointment_datetime], ['vendor_id', $request->vendor_id], ['appointment_status', '!=', 'reject']])->get()->toArray();
        $time_slots = DoctorAvailability::where([['user_id', $request->vendor_id], ['day', $request->day]])->first()->toArray();
        return $this->sendResponse(['book_time_slot' => $appointment_timeslots, 'all_time_slot' => $time_slots], "Fetch Online session list");
        // dd($appointment_timeslots,$time_slots);
    }

    public function bookAppointment(Request $request)
    {
        try {
            DB::beginTransaction();

            $input = $request->all();
            $validate = Validator::make($input, [
                'card_number' => 'required|numeric',
                'holder_name' => 'required|string',
                'csv' => 'required|numeric',
                'expiry' => 'required|date_format:Y-m-d',
                'vendor_id' => [
                    'required',
                    Rule::exists('users', 'id')
                        ->where('type', 'CLIENT'),
                ],
                'category_id' => 'required|exists:categories,id',
                'appointment_type' => 'required|in:home,online,clinic',
                'appointment_datetime' => 'required|date_format:Y-m-d',
                'address' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'vendor_service_id' => 'required|exists:vendor_services,id',
                'time_slot_id' => 'required|exists:time_slots,id',
                'amount' => 'required',
            ]);

            if ($validate->fails()) {
                return $this->sendErrorResponse($validate->errors()->first(), 'Something Missing..', 400);
            }
            $request['user_id'] = Auth::id();
            $card = Card::create($request->only('card_number', 'holder_name', 'csv', 'expiry', 'user_id'));
            $request['card_id'] = $card->id;
            $appointment = Appointment::create($request->only('category_id','appointment_type','address','latitude','longitude','user_id','vendor_id','vendor_service_id','time_slot_id','card_id','appointment_datetime','amount'));
            DB::commit();
            return $this->sendResponse($appointment,"Appointment Booked Successfully...",200);

        } catch (Exception $th) {
            DB::rollBack();
            return $this->sendErrorResponse($th->getMessage(), "something Went Worng....", 500);
        }
    }

    public function getAllUserAppointments(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'status' => 'in:accept,reject,pending,complete',
            'appointment_type' => 'required|in:past,upcoming',
        ]);
        if ($validate->fails()) {
            return $this->sendErrorResponse($validate->errors()->first(), 'Something Missing..', 400);
        }
        $statusFilter = $request->status;
        $appointment_type = $request->appointment_type;
        $appointments = Appointment::with('card','vendor_services','doctor','category')->where('user_id',Auth::id())
        ->when($statusFilter, function($query) use($statusFilter){
            $query->where('appointment_status', $statusFilter);
        })->where(function($query2) use($appointment_type){
            if ($appointment_type == "past") {
                $query2->where('appointment_datetime', '<', date('Y-m-d'));
            }else{
                $query2->where('appointment_datetime', '>', date('Y-m-d'));
            }
        })
        ->paginate($request->limit??5);
        return $this->sendResponse($appointments,"Appointment List fetch successfully...",200);
    }

    public function getUserAppointmentDetail(Request $request, $id)
    {
        $appointments = Appointment::with('card','vendor_services','doctor','category')->where('user_id',Auth::id())->where('id',$id)
        ->first();
        return $this->sendResponse($appointments??[],"Appointment Detail fetch successfully...",200);
    }
}
