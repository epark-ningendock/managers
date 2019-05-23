<?php

namespace App\Mail\HospitalStaff;

use App\HospitalStaff;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $hospital_staff;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(HospitalStaff $hospital_staff)
    {
        $this->hospital_staff = $hospital_staff;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
        ->from('epark@example.com')
        ->subject('[Epark]ご登録ありがとうございます')
        ->view('hospital_staff.email.registered');
    }
}
