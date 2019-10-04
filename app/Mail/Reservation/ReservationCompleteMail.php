<?php

namespace App\Mail;

class ReservationCompleteMail extends ReservationMail
{
    /**
     * 予約API 完了メール
     *
     * @return void
     */
    public function __construct($entity)
    {
        // entity set
        $this->entity = json_decode(json_encode($entity));

        // subject
        $this->subject = $this->entity->process_kbn === 0 ?  '仮受付' : '院外予約変更（仮受付予約変更）';
   
        // template view
        $this->view = 'reservation.email.completed';
    }

}