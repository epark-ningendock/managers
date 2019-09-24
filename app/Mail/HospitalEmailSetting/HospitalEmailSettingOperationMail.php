<?php

namespace App\Mail\HospitalEmailSetting;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class HospitalEmailSettingOperationMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // dd($this->data);
        return $this
            ->from("unei@eparkdock.com")
            ->subject("【EPARK人間ドック】メール設定登録・変更のお知らせ")
            ->view('hospital_email_setting.email.operation-mail');
    }
}
