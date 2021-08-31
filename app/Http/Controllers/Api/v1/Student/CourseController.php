<?php

namespace App\Http\Controllers\Api\v1\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\CategoryResource;
use App\Models\Category;
use App\Models\ContactUs;
use App\Models\Country;
use App\Models\Course;
use App\Models\Manager;
use App\Models\User;
use App\Notifications\ContactUsNotification;
use App\Rules\EmailRule;
use App\Rules\StartWith;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class CourseController extends Controller
{
    public function courses(Request $request)
    {
        return apiSuccess(Course::get());
    }


}
