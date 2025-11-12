<?php

namespace App\Mail;

use App\Models\Order;

class OrderUpdatedMail extends OrderNotificationMail
{
    public function __construct(Order $order)
    {
        parent::__construct(
            $order,
            __('messages.orders.notification.subjects.updated'),
            __('messages.orders.notification.intros.updated')
        );
    }
}
