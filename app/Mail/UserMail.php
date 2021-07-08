<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Restrant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $restrant;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order,$restrant)
    {
        //
        $this->order = $order;
        $this->restrant = $restrant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.user');
    }
}
