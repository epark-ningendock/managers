<?php

namespace App\Mail;

class ReservationCompleteMail extends ReservationMail
{
    /**
     * 予約API 完了メール
     *
     * @return void
     */
    public function __construct($entity, $customer_flg)
    {
        // entity set
        $this->entity = json_decode(json_encode($entity));

        // subject
        $this->subject = '仮受付';
   
        // template view
        $this->view = 'reservation.email.completed';

        $this->customer_flg = $customer_flg;
    }

}
