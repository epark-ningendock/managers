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
    public $attributes;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $attchment, $fax_flg, $attributes = [])
    {
        $this->data = $data;
        $this->attchment = $attchment;
        $this->fax_flg = $fax_flg;
        $this->attributes = $attributes;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ( session('hospital_id') ) {
            $view = 'billing.mail.billing-claim-hospital-confirmation';
        } elseif ( $this->attributes['email_type'] === 'claim_confirmation') {
            if ($this->fax_flg) {
                $view = 'billing.mail.billing-confirmation-fax';
            } else {
                $view = 'billing.mail.billing-confirmation';
            }
        } else {
            if ($this->fax_flg) {
                $view = 'billing.mail.billing-claim-confirmation-fax';
            } else {
                $view = 'billing.mail.billing-claim-confirmation';
            }

        }

        return $this
                ->from(env('MAIL_FROM_ADDRESS'))
                ->subject($this->data['subject'])
                ->attachData(
                    $this->attchment,
                    $this->data['attachment_file_name'] . '.pdf',
                    ['mime' => 'application/pdf',]
                )
                ->view($view, ['billing' => $this->data['billing'], 'attributes' => $this->attributes]);
    }
}
