<?php

namespace App\Mail;

use App\Models\Order;

class OrderSubmittedMail extends OrderNotificationMail
{
    public function __construct(Order $order)
    {
        parent::__construct(
            $order,
            __('messages.orders.notification.subjects.submitted'),
            __('messages.orders.notification.intros.submitted')
        );
    }
}
