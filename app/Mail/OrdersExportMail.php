<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrdersExportMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        protected string $filename,
        protected string $content
    ) {
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this
            ->subject(__('messages.orders.mail.subject'))
            ->view('emails.orders.export')
            ->with([
                'body' => __('messages.orders.mail.body'),
            ])
            ->attachData($this->content, $this->filename, [
                'mime' => 'application/vnd.ms-excel; charset=UTF-8',
            ]);
    }
}
