<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
        $this->order->loadMissing(['customer', 'product']);
    }

    public function build(): self
    {
        return $this
            ->subject(__('messages.orders.notification.subject'))
            ->view('emails.orders.notification')
            ->with([
                'order' => $this->order,
            ]);
    }
}
