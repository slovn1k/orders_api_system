<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class JobOrderNotification implements ShouldQueue
{
    use Queueable;

    protected $order;
    protected $oldStatus;

    public function __construct(Order $order, $oldStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
    }

    public function handle()
    {
        Log::info("Change of status for order {$this->order->order_number}, form {$this->oldStatus} to {$this->order->status}.");
    }
}
