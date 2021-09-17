<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $perPage;
    protected $title = null;
    public function __construct()
    {
        $this->perPage = (integer) request()->get('perPage',15);
    }

}


