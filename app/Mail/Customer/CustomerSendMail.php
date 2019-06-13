<?php

namespace App\Mail\Customer;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerSendMail extends Mailable
{
    use Queueable, SerializesModels;
	public $data;

	/**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
	    $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
	    return $this
		    ->from($this->data->appointed_submissions)
		    ->subject($this->data->subject)
		    ->view('customer.email.customer-mail');
    }
}
