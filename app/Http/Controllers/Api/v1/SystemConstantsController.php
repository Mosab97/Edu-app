<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SystemConstantsController extends Controller
{
    public function system_constants(Request $request)
    {
        return apiSuccess([
            'user_type' => \App\Models\User::user_type,
            'STUDENT_DEFAULT_PHONE' => STUDENT_DEFAULT_PHONE,
        ]);
    }

}
