<?php

namespace App\Events;

use App\Models\Delivery;
use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DriverCompletedOrderEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
