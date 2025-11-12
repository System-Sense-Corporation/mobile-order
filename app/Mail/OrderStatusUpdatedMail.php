<?php

namespace App\Mail;

use App\Models\Order;

class OrderStatusUpdatedMail extends OrderNotificationMail
{
    public function __construct(Order $order, string $statusLabel)
    {
        parent::__construct(
            $order,
            __('messages.orders.notification.subjects.status'),
            __('messages.orders.notification.intros.status', ['status' => $statusLabel]),
            [
                'statusLabel' => $statusLabel,
            ]
        );
    }
}
