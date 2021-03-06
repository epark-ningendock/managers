<?php

namespace App\Mail;

class ReservationChangeMail extends ReservationMail
{
    /**
     * 予約 変更メール
     *
     * @return void
     */
    public function __construct($entity, $customer_flg)
    {
        // entity set
        $this->entity = $entity;

        // subject
        $this->subject = '【EPARK人間ドック】予約変更のお知らせ';
   
        // template view
        $this->view = 'reservation.email.reservation_change';

        $this->customer_flg = $customer_flg;
    }

}
