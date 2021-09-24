<?php

namespace App\Http\Controllers\Api\v1\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\General\ProfileResource;
use App\Http\Resources\Api\v1\Teacher\CourseResource;
use App\Http\Resources\Api\v1\Teacher\GroupResource;
use App\Http\Resources\Api\v1\Teacher\GroupStudentResource;
use App\Http\Resources\Api\v1\Teacher\LessonResource;
use App\Models\Course;
use App\Models\Group;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    private $model;

    public function __construct(Lesson $lesson)
    {
        $this->model = $lesson;
    }

    public function index(Request $request)
    {
        $request->validate(['group_id' => 'required|exists:groups,id']);
        $lessons = $this->model->query()->where(['group_id' => $request->get('group_id')])->get();
        return apiSuccess(LessonResource::collection($lessons));
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'name' => 'required|min:3|max:100'
        ]);
        return apiSuccess(new LessonResource($this->model->create($request->only(['group_id', 'name']))), api('Lesson Created Successfully'));
    }

    public function update(Request $request, $lesson_id)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'name' => 'required|min:3|max:100'
        ]);

        $lesson = $this->model->query()
//            ->whereHas('group', function ($query) {
//            $query->where('teacher_id', apiUser()->id);
//        })
            ->findOrFail($lesson_id);
        $lesson->update($request->only(['group_id', 'name']));
        return apiSuccess(new LessonResource($lesson), api('Lesson Updated Successfully'));
    }

    public function destroy(Request $request, $lesson_id)
    {
        $this->model->query()
//            ->whereHas('group', function ($query) {
//            $query->where('teacher_id', apiUser()->id);
//        })
            ->findOrFail($lesson_id)->delete();
        return apiSuccess(null, api('Lesson Deleted Successfully'));

    }
}
