<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendCoupon extends Mailable
{
    use Queueable, SerializesModels;
    private $user;
    private $list_coupon;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $list_coupon)
    {
        $this->user = $user;
        $this->list_coupon = $list_coupon;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin/coupon/sendcoupon')->with('user',$this->user)->with('list_coupon',$this->list_coupon);
    }
}
