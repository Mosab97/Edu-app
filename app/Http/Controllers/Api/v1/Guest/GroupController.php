<?php

namespace App\Http\Controllers\Api\v1\Guest;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\Student\GroupResource;
use App\Models\Course;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function groups(Request $request)
    {
        if (isset($request->course_id)) {
            $course = Course::query()->with(['groups'])->find($request->course_id);
            if (!isset($course)) return apiError('Wrong Course Id');
            return apiSuccess(GroupResource::collection($course->groups));
        } else {
                $groups = Group::paginate($this->perPage);
            return apiSuccess([
                'items' => GroupResource::collection($groups->items()),
                'paginate' => paginate($groups),
            ]);
        }
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
