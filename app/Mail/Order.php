<?php

namespace App\Mail;

use App\Models\Orders;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Order extends Mailable
{
    use Queueable, SerializesModels;

    public $order_id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order_id)
    {
        $this->order_id = $order_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order_data = Orders::find($this->order_id);

        if (empty($order_data))
            return false;

        try {
            $this->replyTo(config('mail.from.address'), config('mail.from.name'))
                ->cc(config('mail.from.address'), config('mail.from.name'))
                ->subject('Shop - Đơn đặt hàng số ' . $order_data->order_code)
                ->view('frontend.mails.order')
                ->with(['order_data' => $order_data]);

            $order_data->send_mail_status = 1;

            return true;
        } catch (\Exception $e) {
            \Log::error('Send Mail Order to Customer:' . $e->getMessage());
            return false;
        }
    }
}
