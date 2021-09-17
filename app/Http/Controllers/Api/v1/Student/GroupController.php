<?php

namespace App\Http\Controllers\Api\v1\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\Student\GroupResource;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function groupsByCourse(Request $request, $course_id)
    {
        $groups = Group::query()->where('course_id', $course_id)->with(['course'])->get();
        if (count($groups) == 0) return apiError('Wrong Course Id');
        return apiSuccess(GroupResource::collection($groups));
    }

    public function groupsByLevel(Request $request, $course_id, $level_id)
    {
        $age_id = $request->get('age_id', false);
        $groups = Group::query()->where(['course_id' => $course_id, 'level_id' => $level_id,])->when($age_id, function ($query) use ($age_id) {
            $query->where('age_id', $age_id);
        })->with(['course', 'level', 'age'])->get();
//        if (count($groups) == 0) return apiError('Wrong Course Id');
        return apiSuccess(GroupResource::collection($groups));
    }

    public function my_groups(Request $request)
    {
        $student = apiUser();
        if (!isset($student)) return apiError('Wrong Student');
//        return apiSuccess(GroupResource::collection($student->groups()->whereHas('group')->with(['group'])->withCount('group.students')->get()));
        return apiSuccess(GroupResource::collection($student->student_groups()->with(['group'])->get()->pluck('group')));
    }

    public function group(Request $request, $group_id)
    {
        $group = Group::find($group_id);
        if (!isset($group)) return apiError('Wrong Group Id');
        return apiSuccess(new GroupResource($group));
    }
}
