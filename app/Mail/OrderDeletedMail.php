<?php

namespace App\Mail;

use App\Models\Order;

class OrderDeletedMail extends OrderNotificationMail
{
    public function __construct(Order $order)
    {
        parent::__construct(
            $order,
            __('messages.orders.notification.subjects.deleted'),
            __('messages.orders.notification.intros.deleted')
        );
    }
}
