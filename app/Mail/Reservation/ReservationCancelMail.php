<?php

namespace App\Mail;

class ReservationCancelMail extends ReservationMail
{
    protected $context;
    /**
     * 予約API 予約キャンセルメール
     *
     * @return void
     */
    public function __construct($entity, $customer_flg)
    {
        // subject
        $this->subject = '【EPARK人間ドック】予約キャンセルのお知らせ';

        // entity set
        $this->entity = json_decode(json_encode($entity));

        // template view
        $this->view = 'reservation.email.canceled';

        $this->customer_flg = $customer_flg;
    }
}
