<?php

namespace App\Mail;

use App\Enums\Gender;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Config;

use Carbon\Carbon;

use Log;

// 送信元
define('EPARK_MAIL_FROM', config('mail.from.address'));
class ReservationMail extends Mailable
{
    use Queueable, SerializesModels;

    /** 件名 */
    public $subject = '';

    /** template view */
    public $view = '';

    /* mail entity */
    public $entity;

    public $customer_flg;

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // 施設住所
        $facility_addr = $this->entity->hospital->district_code->prefecture->name . ' '
            . $this->entity->hospital->district_code->name . ' '
            . $this->entity->hospital->address1 . ' ' . $this->entity->hospital->address2;

        $options = $this->_options($this->entity->reservation_options);
 
        // コース料金＋オプション総額
        $reservation_options_price = $this->_calc_reservation_options_price($this->entity->reservation_options);
        $course_options_price = intval($this->entity->course->price) + $reservation_options_price;

        // コース料金＋オプション総額＋調整金額
        $adjustment_price = intval($this->entity->adjustment_price);
        $course_amount = $course_options_price + $adjustment_price;

        // 町村字番地/建物名分割
        $pieces = explode(' ', $this->entity->customer->address2);

        // 医療機関の選び方
        $chooses = ['自由選択', '施設一覧から選択', ''];
        $is_choose = $this->entity->is_choose ?? 2;
        $choose = $chooses[$is_choose];

        // 医療施設からの質問
        $questions = $this->_questions($this->entity->reservation_answers);
        $questions = !empty($questions) ? $questions : [];

        // 電話が繋がりやすい時間帯
        $timezones =  ['', '特に希望なし', '9時～12時', '', '12時～13時', '', '', '', '13時～17時', ];
        $tel_timezone = $this->entity->tel_timezone ?? 4;
        $timezone = $timezones[$tel_timezone];

        // キャンセル変更受付期限日
        $reservation_date = $this->entity->reservation_date;
        $cancellation_deadline = intval($this->entity->course->cancellation_deadline);
        $cancellation_date = new Carbon($reservation_date);
        $cancellation_date = $cancellation_date->subDays($cancellation_deadline);
        $cancellation_date = date('Y/m/d', strtotime($cancellation_date));

        // 性別
        if ($this->entity->customer->sex == Gender::MALE) {
            $sex = '男';
        } else {
            $sex = '女';
        }

        return $this
            ->from(EPARK_MAIL_FROM)
            ->subject($this->subject)
            ->view($this->view)
            ->with([
                '姓' => $this->entity->customer->family_name ?? '',
                '名' => $this->entity->customer->first_name ?? '',
                '医療施設名' => $this->entity->hospital->name ?? '',
                '医療施設住所' => $facility_addr,
                '医療施設電話番号' => $this->entity->hospital->tel ?? '',
                '検査コース名' => $this->entity->course->name ?? '',
                'コース料金' => $this->entity->course->price ?? '',
                // 'オプション名' => '',
                // 'オプション金額' => '',
                'options' => $options,

                'コース料金＋オプション総額' => $course_options_price,
                '調整金額' => $this->entity->adjustment_price ?? '0',
                'コース料金＋オプション総額＋調整金額' => $course_amount,
                '支払方法' => $this->entity->payment_method ?? '',
                'カード決済額' => $this->entity->settlement_price ?? '',
                'キャシュポ利用額' => $this->entity->cashpo_used_price ?? '',
                '未決済金額' => $this->entity->amount_unsettled ?? '',
                '確定日' => $reservation_date,
                '受付日' => date('Y/m/d', strtotime($this->entity->created_at)),
                '受付時間' => $this->entity->start_time_hour . ':' . $this->entity->start_time_min,

                '備考' => $this->entity->reservation_memo ?? '',

                '第一希望日' => date('Y/m/d', strtotime($this->entity->reservation_date)) ?? '',
                '第二希望日' => '1970/01/01' !== date('Y/m/d', strtotime($this->entity->second_date)) ?
                    date('Y/m/d', strtotime($this->entity->second_date)) : '',
                '第三希望日' => '1970/01/01' !== date('Y/m/d', strtotime($this->entity->third_date)) ?
                    date('Y/m/d', strtotime($this->entity->third_date)) : '',
                '姓読み仮名' => $this->entity->customer->family_name_kana ?? '',
                '名読み仮名' => $this->entity->customer->first_name_kana ?? '',
                '性別' =>  $sex,
                '年' => date('Y', strtotime($this->entity->customer->birthday)),
                '月' => date('m', strtotime($this->entity->customer->birthday)),
                '日' => date('d', strtotime($this->entity->customer->birthday)),
                '郵便番号' => $this->entity->customer->postcode ?? '',
                '都道府県' => $this->entity->customer->prefecture->name ?? '',
                '市区群' => $this->entity->customer->address1 ?? '',
                '町村番地' => $pieces[0] ?? '',
                '建物名' => $pieces[1] ?? '',
                '電話番号' => $this->entity->customer->tel,
                'メールアドレス' => $this->entity->customer->email,
                '施設の選び方' => $choose,
                'キャンペーンコード' => $this->entity->campaign_code,
                'questions' => $questions,
                '電話が繋がりやすい時間帯' => $timezone,
                '所属する健康保険組合名' => $this->entity->insurance_assoc ?? '',
                'キャンセル変更受付期限日' => $cancellation_date,
                'customer_flg' => $this->customer_flg,
                'status' => $this->entity->reservation_status,
                'process_kbn' => $this->entity->process_kbn,
                '管理画面URL' => $this->createURL(),
            ]);
    }

    /**
     * 予約オプション要素作成
     *
     * @param  予約オプション情報  $reservation_options
     * @return 予約オプション情報
     */
    private function _options($reservation_options): array
    {
        $options = collect(json_decode(json_encode($reservation_options)))->filter(function ($r) {
            return isset($r->option);
        });

        $results = $options->map(function ($o) {
            return ['name' => $o->option->name, 'price' => $o->option_price];
        });
        return $results->isEmpty() ? [] : $results->toArray();
    }

    /**
     * 予約オプション価格要素作成
     *
     * @param  予約オプション情報  $reservation_options
     * @return 予約オプション情報
     */
    private function _calc_reservation_options_price($reservation_options): int {

        $options = collect(json_decode(json_encode($reservation_options)))->filter(function ($r) {
            return isset($r->option);
        });
        $result = 0;
        foreach($options as $o) {
           $result += intval($o->option_price);
        }
        return $result;
    }

    /**
     * 予約回答要素作成
     *
     * @param  予約回答情報  $reservation_answers
     * @return 予約回答情報
     */
    private function _questions($reservation_answers): array
    {
        $results = [];

        foreach ($reservation_answers as $a) {
            if ($a->answer01 == 1
                || $a->answer02 == 1
                || $a->answer03 == 1
                || $a->answer04 == 1
                || $a->answer05 == 1
                || $a->answer06 == 1
                || $a->answer07 == 1
                || $a->answer08 == 1
                || $a->answer09 == 1
                || $a->answer10 == 1) {
                $ans = '';
                if ($a->answer01 == 1) {
                    $ans = $a->question_answer01 . '、';
                }
                if ($a->answer02 == 1) {
                    $ans = $ans . $a->question_answer02 . '、';
                }
                if ($a->answer03 == 1) {
                    $ans = $ans . $a->question_answer03 . '、';
                }
                if ($a->answer04 == 1) {
                    $ans = $ans . $a->question_answer04 . '、';
                }
                if ($a->answer05 == 1) {
                    $ans = $ans . $a->question_answer05 . '、';
                }
                if ($a->answer06 == 1) {
                    $ans = $ans . $a->question_answer06 . '、';
                }
                if ($a->answer07 == 1) {
                    $ans = $ans . $a->question_answer07 . '、';
                }
                if ($a->answer08 == 1) {
                    $ans = $ans . $a->question_answer08 . '、';
                }
                if ($a->answer09 == 1) {
                    $ans = $ans . $a->question_answer09 . '、';
                }
                if ($a->answer10 == 1) {
                    $ans = $ans . $a->question_answer10 . '、';
                }
                $ans = rtrim($ans, '、');
                $results[] = ['question_title' => $a->question_title, 'answer' => $ans];

            }
        }
        return $results;
    }

    /**
     * @return string
     */
    private function createURL() {
        return (empty($_SERVER['HTTPS']) ? 'http://' : 'https://').$_SERVER['HTTP_HOST'];
    }
}
