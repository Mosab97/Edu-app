<?php

namespace App\Http\Controllers\Api\v1\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\Student\CourseResource;
use App\Http\Resources\Api\v1\Student\GroupResource;
use App\Http\Resources\Api\v1\Student\QuestionResource;
use App\Models\Course;
use App\Models\Group;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function courses(Request $request)
    {
        return apiSuccess(CourseResource::collection(Course::get()));
    }

    public function questions(Request $request, $course_id)
    {
        $course = Course::with(['questions', 'questions.answers'])->find($course_id);
        if (!isset($course)) return apiError('Wrong Course Id');
        return apiSuccess(QuestionResource::collection($course->questions));
    }

    public function groups(Request $request, $course_id)
    {
        $course = Course::with(['groups'])->find($course_id);
        if (!isset($course)) return apiError('Wrong Course Id');
        return apiSuccess(GroupResource::collection($course->groups));
    }

    public function my_groups(Request $request)
    {
        $student = user('student');
        if (!isset($student)) return apiError('Wrong Student');
//        return apiSuccess(GroupResource::collection($student->groups()->whereHas('group')->with(['group'])->withCount('group.students')->get()));
        return apiSuccess(GroupResource::collection($student->groups()->with(['group'])->get()->pluck('group')));
    }

    public function group(Request $request, $group_id)
    {
        $group = Group::find($group_id);
        if (!isset($group)) return apiError('Wrong Group Id');
        return apiSuccess(new GroupResource($group));
    }
}
