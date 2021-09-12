<?php

namespace App\Http\Controllers\Api\v1\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\Teacher\CourseResource;
use App\Http\Resources\Api\v1\Teacher\GroupResource;
use App\Models\Course;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{


    public function groups_course_id(Request $request, $course_id)
    {
        $course = Course::with(['groups'])->find($course_id);
        if (!isset($course)) return apiError('Wrong Course Id');
        return apiSuccess(GroupResource::collection($course->groups));
    }

    public function my_groups(Request $request)
    {
        $teacher = user('teacher');
        if (!isset($teacher)) return apiError('Wrong Teacher');
        return apiSuccess(GroupResource::collection($teacher->groups()->with('course')->get()));
    }

    public function group(Request $request, $group_id)
    {
        $group = Group::find($group_id);
        if (!isset($group)) return apiError('Wrong Group Id');
        return apiSuccess(new GroupResource($group));
    }

    public function add_group(Request $request)
    {
        //        validations
        $request->validate([
            'name' => 'required|min:3|max:100',
            'course_id' => 'required|exists:courses,id',
            'price' => 'required|numeric',
            'image' => 'sometimes|image',
            'video' => 'sometimes|mimes:mp4,mov,ogg,qt | max:20000',
            'level_id' => 'required|exists:levels,id',
            'students_number_max' => 'required|numeric',
            'number_of_live_lessons' => 'required|numeric',
            'number_of_exercises_and_games' => 'required|numeric',
            'course_date_and_time' => 'date_format:Y-m-d H:i',
            'what_will_i_learn' => 'sometimes|min:3|max:500',
            'gender' => 'sometimes|in:' . implode(',', Gender),
        ]);
        $data = $request->except(['image', 'video']);
        if ($request->hasFile('image')) $data['image'] = $this->uploadImage($request->file('image'), 'groups');
        if ($request->hasFile('video')) $data['video'] = $this->uploadImage($request->file('video'), 'groups');

        $data['teacher_id'] = user('teacher')->id;
        $group = Group::create($data);
        return apiSuccess(new GroupResource($group));
    }

    public function update_group(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        //        validations
        $request->validate([
            'name' => 'required|min:3|max:100',
            'course_id' => 'required|exists:courses,id',
            'price' => 'required|numeric',
            'image' => 'sometimes|image',
            'video' => 'sometimes|mimes:mp4,mov,ogg,qt | max:20000',
            'level_id' => 'required|exists:levels,id',
            'students_number_max' => 'required|numeric',
            'number_of_live_lessons' => 'required|numeric',
            'number_of_exercises_and_games' => 'required|numeric',
            'course_date_and_time' => 'date_format:Y-m-d H:i',
            'what_will_i_learn' => 'sometimes|min:3|max:500',
            'gender' => 'sometimes|in:' . implode(',', Gender),
        ]);
        $data = $request->except(['image', 'video']);
        if ($request->hasFile('image')) $data['image'] = $this->uploadImage($request->file('image'), 'groups');
        if ($request->hasFile('video')) $data['video'] = $this->uploadImage($request->file('video'), 'groups');
        $data['teacher_id'] = user('teacher')->id;
         $group->update($data);
        return apiSuccess(new GroupResource($group));
    }
}