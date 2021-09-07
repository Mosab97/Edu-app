<?php

namespace App\Http\Controllers\Api\v1\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\CourseResource;
use App\Http\Resources\Api\v1\QuestionResource;
use App\Models\Course;
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
}
