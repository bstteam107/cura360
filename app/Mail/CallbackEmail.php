<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CallbackEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Mail type i.e. `customer` or `admin`.
     *
     * @var array
     */
    protected $mailType = ['customer', 'admin'];

    /**
     * Selected mail type.
     *
     * @var string
     */
    public $selectedMailType;

    /**
     * Create a new mailable instance.
     *
     * @param  array  $data
     * @param  string  $mailType
     * @return void
     */
    public function __construct(
        public $data,
        $mailType
    )
    {
        $this->data = $data;

        $this->selectedMailType = in_array($mailType, $this->mailType) ? $mailType : 'customer';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->selectedMailType === 'customer') {
            return $this->mailToCustomer();
        }

        return $this->mailToAdmin();
    }

    /**
     * Mail to customer.
     *
     * @return $this
     */
    public function mailToCustomer()
    {
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->to($this->data['Email'])
            ->subject('Request a call back')
            ->view('shop::emails.customer.callback')->with('data', $this->data);
    }

    /**
     * Mail to admin.
     *
     * @return $this
     */
    public function mailToAdmin()
    {
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->to(core()->getAdminEmailDetails()['email'])
            ->subject('Request a call back')
            ->view('shop::emails.admin.callback')->with('data', $this->data);
    }
}
