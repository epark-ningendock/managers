<?php

namespace App\Mail;

class ReservationReceptionCompleteFaxToMail extends ReservationMail
{
    /**
     * 予約API 完了FaxToMail
     *
     * @return void
     */
    public function __construct($entity)
    {
        // entity set
        $this->entity = $entity;

        // subject
        $this->subject = '【EPARK人間ドック】予約確定のお知らせ';
   
        // template view
        $this->view = 'reservation.fax.reception_completed';
    }

}
