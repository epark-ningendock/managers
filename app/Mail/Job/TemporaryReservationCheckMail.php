<?php

namespace App\Mail\Job;

use Carbon\Carbon;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class TemporaryReservationCheckMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $context;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $context)
    {
        $this->context = $context;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = '[重要]仮受付予約情報のお知らせ';

        return $this->subject($subject)
                    ->text('batch.email.temporary-reservation-check-mail')
                    ->with(['context' => $this->context,]);
    }
}
