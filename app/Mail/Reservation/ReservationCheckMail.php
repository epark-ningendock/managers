<?php

namespace App\Mail\Reservation;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReservationCheckMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $total;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
        
        $this->total = $data['reservation']->course->price + $data['reservation']->adjustment_price;

        foreach ($data['reservation']->reservation_options as $reservation_option) {
            $this->total += $reservation_option->option->price;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from(env('EPARK_EMAIL_ADDRESS'))
            ->subject("【EPARK人間ドック】受付情報登録・変更のお知らせ")
            ->view('reservation.email.reservation-mail');
    }
}
