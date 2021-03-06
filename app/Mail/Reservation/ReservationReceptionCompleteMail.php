<?php

namespace App\Mail;

class ReservationReceptionCompleteMail extends ReservationMail
{
    /**
     * 予約 確定メール
     *
     * @return void
     */
    public function __construct($entity, $customer_flg)
    {
        // entity set
        $this->entity = $entity;

        // subject
        $this->subject = '【EPARK人間ドック】受付確定のお知らせ';
   
        // template view
        $this->view = 'reservation.email.reception_completed';

        $this->customer_flg = $customer_flg;
    }

}
