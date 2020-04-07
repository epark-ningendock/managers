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
        $this->entity = json_decode(json_encode($entity));

        // subject
        $this->subject = '受付確定';
   
        // template view
        $this->view = 'reservation.email.reception_completed';

        $this->customer_flg = $customer_flg;
    }

}
