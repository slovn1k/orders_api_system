<?php

namespace App\Events;

use App\Models\Order;

class OrderStatusChange
{
    public $order;
    public $oldStatus;

    public function __construct(Order $order, $oldStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
    }
}
