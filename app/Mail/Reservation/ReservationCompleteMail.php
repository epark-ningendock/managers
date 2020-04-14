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
        $this->subject = '【EPARK人間ドック】予約受付のお知らせ';
   
        // template view
        $this->view = 'reservation.email.completed';

        $this->customer_flg = $customer_flg;
    }

}
