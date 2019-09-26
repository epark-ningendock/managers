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
    public $attchment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $attchment)
    {
        $this->data = $data;
        $this->attchment = $attchment;
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
            ->attachData($this->attchment->output(), $this->data['attachment_file_name'] . 'pdf',[
                'mime' => 'application/pdf',
            ])
            ->view('billing.mail.billing-confirmation', ['billing' => $this->data['billing']]);
    }
}
