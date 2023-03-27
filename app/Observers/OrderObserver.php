<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderStatus;
use Carbon\Carbon;
use Quisui\OrderBasicNotification\NotificationOrderStatusUpdater;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Check if the order_status_id has changed
        if ($order->order_status_id != $order->getOriginal('order_status_id')) {
            $orderStatus = OrderStatus::find($order->order_status_id);
            new NotificationOrderStatusUpdater([
                'order_uuid' => $order->uuid,
                'new_status' => $orderStatus->title,
                'message' => 'Order Status Has changed to: ' . $orderStatus->title,
                'timestamp' => Carbon::now(),
                'webhook_url' => config('app.webhook_challenge_url'),
                'webhook_http_method' => 'post'
            ]);
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
