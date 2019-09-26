<?php

namespace App\Mail\Billing;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BillingConfirmationSendMail extends Mailable
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
        $this->data = $data;
    }


	public function emailFrom() {
		return ( env('EPARK_UNEI_MAIL_FROM') ) ? env('EPARK_UNEI_MAIL_FROM') : env('MAIL_FROM_ADDRESS');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from($this->emailFrom())
            ->subject($this->data['subject'])
            ->view('billing.mail.billing-confirmation', []);
    }
}
