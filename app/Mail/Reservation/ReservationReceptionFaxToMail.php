<?php

namespace App\Mail;

class ReservationReceptionFaxToMail extends ReservationMail
{
    /**
     * 予約受付メール
     *
     * @return void
     */
    public function __construct($entity, $customer_flg)
    {
        // entity set
        $this->entity = $entity;

        // subject
        $this->subject = '【EPARK人間ドック】受付のお知らせ';
   
        // template view
        $this->view = 'reservation.fax.reception';

        $this->customer_flg = $customer_flg;
    }

}
