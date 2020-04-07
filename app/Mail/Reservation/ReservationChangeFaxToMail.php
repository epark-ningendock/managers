<?php

namespace App\Mail;

class ReservationChangeFaxToMail extends ReservationMail
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
        $this->subject = '【EPARK人間ドック】予約変更のお知らせ';
   
        // template view
        $this->view = 'reservation.fax.reservation_change';
    }

}
