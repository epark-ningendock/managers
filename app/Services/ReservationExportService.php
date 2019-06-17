<?php
namespace App\Services;

use App\Reservation;
use Carbon\Carbon;
use App\Customer;

class ReservationExportService
{
    protected $reservation;
    public function __construct(
        Reservation $reservation
    ) {
        $this->reservation = $reservation;
    }

    public function operationCsv($request)
    {

        $query = $this->reservation
            ->byRequest($request)
            ->with(['hospital', 'course', 'customer'])
            ->orderBy('created_at', 'desc');

        $reservation_lists = $query->get();

        $list_map = $reservation_lists->map(function ($reservation_lists) {
            $option_list = $reservation_lists->course->options->map(function ($options) {
                $option_list['name'] =  $options->name;
                $option_list['price'] =  $options->price;
                return $option_list;
            });

            $option_list = $option_list->take(10)->toArray();

            $option_csv = [];

            for ($i = 0; $i <= 9; $i++) {
                $option_csv[] = isset($option_list[$i]['name']) ? $option_list[$i]['name'] : '';
                $option_csv[] = isset($option_list[$i]['price']) ? $option_list[$i]['price'] : '';
            }

            $list = [
                $reservation_lists->reservation_date,//予約日
                $reservation_lists->terminal_type,//'医院/院外'
                $reservation_lists->channel,//'媒体'
                Reservation::$english_names[$reservation_lists->reservation_status],//区分
                Reservation::$is_billable[$reservation_lists->is_billable],//課金・未課金
                $reservation_lists->site_code,//サイトコード
                $reservation_lists->cancel_date,//キャンセル日
                $reservation_lists->member_number,//顧客番号
                '',//エリア
                $reservation_lists->hospital->principal,//事業者名
                $reservation_lists->hospital->name,//医療機関名
                $reservation_lists->claim_month,//請求月
                $reservation_lists->completed_date,//来院日
                $reservation_lists->course_id,//検査コースID（ライングループNo）
                $reservation_lists->course->name,//予約コース
                '',//総額
                $reservation_lists->course->price,//税抜き価格
                $reservation_lists->tax_included_price,//コース料金
                '',//オプション名称
                '',//オプション料金
                '',//手数料（税抜き）
                '',//請求金額
                '',//プランコード
                '',//ROOKプラン
                '',//OP
                '',//成果コース
                $reservation_lists->customer->member_number,//顧客ID
                '',//会員番号
                $reservation_lists->customer->name,//顧客名（受診者名）
                $reservation_lists->customer->name_kana,//ふりがな
                Customer::$sex[$reservation_lists->customer->sex],//性別
                $reservation_lists->customer->tel,//連絡先
                $reservation_lists->customer->birthday,
                Carbon::parse($reservation_lists->customer->birthday)->age,//年齢
                $reservation_lists->customer->email,//メールアドレス
                $reservation_lists->customer->postcode,//郵便番号
                $reservation_lists->customer->prefecture->name,//都道府県
                $reservation_lists->customer->address,//住所
                $reservation_lists->reservation_memo,//受付・予約メモ
                $reservation_lists->todays_memo,//当日メモ
                $reservation_lists->internal_memo,//内部用メモ
            ];

            array_splice($list, 18, 0, $option_csv);

            return $list;
        });

        $file = new \SplFileObject(storage_path('csv/file.csv'), 'w');

        $csv_head = $this->reservationCsvHeader();

        $list_map_array[] = $csv_head;
        $list_map_array += $list_map->toArray();

        foreach ($list_map_array as $fields) {
            mb_convert_variables('SJIS-win', 'UTF-8', $fields);
            $file->fputcsv($fields);
        }

        $headers = [
            'Content-Type' => 'text/plain',
        ];

        return response()
            ->download($file, Carbon::now()->format('YmdHij').'.csv', $headers);
    }

    private function reservationCsvHeader()
    {
        $option_list_head = [];

        $option_head_name = 'オプション名称_';
        $option_head_price = 'オプション料金_';
        for ($i = 1; $i <= 10; $i++) {
            $option_list_head[] = $option_head_name.$i;
            $option_list_head[] = $option_head_price.$i;
        }

        $csv_head = [
            '予約日',
            '医院/院外',
            '媒体',
            '区分',
            '課金・未課金',
            'サイトコード',
            'キャンセル日',
            '顧客番号',
            'エリア',
            '事業者名',
            '医療機関名',
            '請求月',
            '来院日',
            '検査コースID（ライングループNo）',
            '予約コース',
            '総額',
            '税抜き価格',
            'コース料金',
            '手数料（税抜き）',
            '請求金額',
            'プランコード',
            'ROOKプラン',
            'OP',
            '成果コース',
            '顧客ID',
            '会員番号',
            '顧客名（受診者名）',
            'ふりがな',
            '性別',
            '連絡先',
            '生年月日',
            '年齢',
            'メールアドレス',
            '郵便番号',
            '都道府県',
            '住所',
            '受付・予約メモ',
            '当日メモ',
            '内部用メモ'
        ];
        array_splice($csv_head, 18, 0, $option_list_head);
        return $csv_head;
    }
}
