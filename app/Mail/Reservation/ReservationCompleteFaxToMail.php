<?php

namespace App\Mail;

class ReservationCompleteFaxToMail extends ReservationMail
{
    /**
     * 予約API 完了FaxToMail
     *
     * @return void
     */
    public function __construct($entity)
    {
        // entity set
        $this->entity = json_decode(json_encode($entity));

        // subject
        $this->subject = '【EPARK人間ドック】予約受付のお知らせ';
   
        // template view
        $this->view = 'reservation.fax.completed';
    }

}
