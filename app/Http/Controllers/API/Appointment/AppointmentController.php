<?php

namespace App\Http\Controllers\Api\Appointment;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewAppointment;
use App\Notifications\AppointmentStatusUpdate;
use Exception;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        request()->validate([
            'filter' => 'in:all,pending,accept',
        ]);
        $filter = request()->get('filter');
        $appointments = Auth::user()->appointments()->with(['user:id,name', 'category:id,name'])
            ->where(function ($query) use ($filter) {
                if ($filter != 'all' && $filter != '') {
                    $query->where('appointment_status', $filter);
                }
            })
            ->paginate();
        return $this->sendResponse($appointments, 'Appointments Retrieved', 200);
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
            'category_id'           => 'required|numeric|exists:categories,id',
            'appointment_type'      => 'required|in:home,clinic,online',
            'appointment_datetime'  => 'required|date_format:Y-m-d H:i:s',
            'address'               => 'required_if:appointment_type,==,home',
            'latitude'              => 'required_if:appointment_type,==,home',
            'longitude'             => 'required_if:appointment_type,==,home',
            'vendor_id'             => 'required|numeric|exists:users,id',
        ]);
        $appointment = Auth::user()->userAppointments()->create($request->all());
        $message = '';
        try {
            $vendor = User::find($appointment->vendor_id);
            $service = Categories::find($appointment->category_id);
            $admin = User::where('type', 'ADMIN')->first();
            $admin->notify(new NewAppointment($vendor, Auth::user(), $service->name, $appointment));
            $vendor->notify(new NewAppointment($vendor, Auth::user(), $service->name, $appointment));
            Auth::user()->notify(new NewAppointment($vendor, Auth::user(), $service->name, $appointment));
        } catch (Exception $e) {
            $message = 'Email send Failed';
        }

        return $this->sendResponse($appointment, 'Appointment Added Successfully.' . $message, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $appointment = Appointment::find($id);
        return $this->sendResponse($appointment, 'Appointment Retrieved Successfully', 200);
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
            'appointment_status'    => 'required|in:accept,reject,commplete',
            'reject_reason'         => 'required_if:appointment_status,==,reject',
        ]);
        if (!$request->appointment_status == "accept") {
            $request['link'] = "";
        }
        $appointment = Appointment::find($id);
        $appointment->update($request->all());

        $message = '';
        try {
            $vendor = User::find($appointment->vendor_id);
            $service = Categories::find($appointment->category_id);
            $admin = User::where('type', 'ADMIN')->first();
            $admin->notify(new AppointmentStatusUpdate($vendor, Auth::user(), $service->name, $appointment));
            $vendor->notify(new AppointmentStatusUpdate($vendor, Auth::user(), $service->name, $appointment));
            Auth::user()->notify(new AppointmentStatusUpdate($vendor, Auth::user(), $service->name, $appointment));
        } catch (Exception $e) {
            $message = 'Email send Failed';
        }
        return $this->sendResponse([], 'Appointment Update Successfully', 200);
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
