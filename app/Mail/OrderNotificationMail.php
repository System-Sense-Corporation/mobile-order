<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<string, mixed>  $viewData
     */
    public function __construct(
        public Order $order,
        protected string $subjectLine,
        protected string $introLine,
        protected array $additionalViewData = []
    ) {
        $this->order->loadMissing(['customer', 'product']);
    }

    public function build(): self
    {
        return $this
            ->subject($this->subjectLine)
            ->view('emails.orders.notification')
            ->with(array_merge([
                'order' => $this->order,
                'subject' => $this->subjectLine,
                'intro' => $this->introLine,
                'outro' => __('messages.orders.notification.outro'),
            ], $this->additionalViewData));
    }
}
