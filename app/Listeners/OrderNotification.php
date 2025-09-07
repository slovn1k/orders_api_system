<?php

namespace App\Listeners;

use App\Events\OrderStatusChange;
use App\Jobs\JobOrderNotification;

class OrderNotification
{
    public function handle(OrderStatusChange $event)
    {
        JobOrderNotification::dispatch($event->order, $event->oldStatus);
    }
}
