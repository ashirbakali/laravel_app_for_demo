<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Service;
use App\Models\UserCourse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function getAllCoursesList(Request $request)
    {
        $search = $request->search;
        $courses = Service::where('type', 'course')
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'LIKE', '%' . $search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate($request->limit ?? 5);
        return $this->sendResponse($courses, 'Courses list fetched');
    }

    public function getCourseDetail(Request $request, $id)
    {
        $courses = Service::find($id);
        return $this->sendResponse($courses, 'Courses Detail Fetch');
    }


    public function buyCourse(Request $request)
    {
        try {
            DB::beginTransaction();
            $validate = Validator::make($request->all(), [
                'card_number' => 'required|numeric',
                'holder_name' => 'required|string',
                'csv' => 'required|numeric',
                'expiry' => 'required|date_format:Y-m-d',
                'course_id' => [
                    'required',
                    Rule::exists('services', 'id')
                        ->where('type', 'course'),
                ],
                'amount' => 'required|numeric'
            ]);

            if ($validate->fails()) {
                return $this->sendErrorResponse($validate->errors()->first(), 'Something Missing..', 400);
            }
            $request['user_id'] = Auth::id();
            $card = Card::create($request->only('card_number', 'holder_name', 'csv', 'expiry', 'user_id'));
            $request['card_id'] = $card->id;
            $userCourse = UserCourse::create($request->only('course_id', 'amount', 'user_id', 'card_id'));
            DB::commit();
            return $this->sendResponse($userCourse, "Course buy Successfully...", 200);
        } catch (Exception $th) {
            DB::rollBack();
            return $this->sendErrorResponse($th->getMessage(), "something Went Worng....", 500);
        }
    }


    public function getUserPurchaseCourses(Request $request)
    {
        $courses = UserCourse::where('user_id', Auth::id())->get();
        return $this->sendResponse($courses, "User Purchase Course Fetch", 200);
    }
}
