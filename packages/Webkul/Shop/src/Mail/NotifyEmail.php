<?php

namespace Webkul\Shop\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyEmail extends Mailable
{
    use Queueable, SerializesModels;

     public function __construct(public $data)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->to(core()->getAdminEmailDetails()['email'])
            ->subject('Notify By Paytomorrow')
            ->view('shop::emails.admin.notify')->with('data', $this->data);
    }


    /**
     * Mail to admin.
     *
     * @return $this
     */
    
}
