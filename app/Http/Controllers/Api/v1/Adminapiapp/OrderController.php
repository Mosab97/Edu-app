<?php

namespace App\Http\Controllers\Api\v1\Adminapiapp;

use App\Http\Controllers\Api\v1\Controller;

use App\Http\Resources\AdminApi\OrdersResource;
use App\Http\Resources\AdminApiProfileResource;
use App\Models\Adminapiapp;
use App\Models\Order;
use App\Rules\EmailRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OrderController extends Controller
{
    private $model;

    public function __construct(Order $order)
    {
        $this->model = $order;
    }

    public function index(Request $request)
    {
        $orders = $this->model->with(['user','distributor','order_products'])->pendingOrOnWayOrder()->get();
        return apiSuccess(OrdersResource::collection($orders));
    }

}
