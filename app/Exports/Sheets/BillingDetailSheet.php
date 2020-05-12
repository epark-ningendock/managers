<?php

namespace App\Exports\Sheets;

use App\Billing;
use App\Enums\ReservationStatus;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class BillingDetailSheet implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle
{
    private $dataCollection;

    private $counter = 1;
    private $startedDate;
    private $endedDate;
	private $selectedMonth;

	public function __construct($collection, $startedDate, $endedDate, $selectedMonth)
    {
        $this->dataCollection = $collection;
        $this->startedDate = $startedDate;
        $this->endedDate = $endedDate;
	    $this->selectedMonth = $selectedMonth;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->reservationCollection();
    }

    public function reservationCollection()
    {

        $row = [];
        foreach( $this->dataCollection as $billing ) {

            $reservations = $billing->hospital->reservationByCompletedDate($this->startedDate, $this->endedDate)->sortBy('hospital_id');

            if ( isset($reservations) )

                foreach( $reservations as $key => $reservation) {

                    if ( $reservation->terminal_type == '2' || $reservation->terminal_type == '3' ) {
                        $channel = 'WEB';
                    } else {
                        $channel = 'TEL';
                    }

                    $comp_date = $reservation->reservation_date->format('Y/m/d');
//                    if (isset($reservation->completed_date)) {
//                        $comp_date = $reservation->completed_date->format('Y/m/d');
//                    }

                    $hp_link_status = '';
                    if ( ($reservation->site_code == 'HP') && ( $reservation->fee == 0) && ( $reservation->reservation_status->value == '4') ) {
                        $hp_link_status = 'HPリンク（キャンセル）';
                    } elseif ( ($reservation->site_code == 'HP') && ( $reservation->fee == 0) ) {
                        $hp_link_status = 'HPリンク';
                    }

                    $the_amount = $reservation->tax_included_price + $reservation->adjustment_price + $reservation->reservation_options->pluck('option_price')->sum();

                    $tax_rate = 10;
                    if (!is_null($reservation->tax_rate) && is_numeric($reservation->tax_rate) && $reservation->tax_rate != 0) {
                        $tax_rate = $reservation->tax_rate;
                    }

                    $row[] = [
                        // $reservation->hospital_id, sorting testing
                        $billing->created_at->format('Y/m'),
                        $billing->hospital->contract_information->property_no,
                        $billing->hospital->contract_information->contractor_name,
                        $billing->hospital->name,
                        $channel,
                        $reservation->site_code ?? '',
                        $comp_date,
                        $hp_link_status,
                        $reservation->course->name,
                        $reservation->reservation_options->isEmpty() ? '' : '有',
                        number_format($the_amount),
                        number_format($the_amount / (($tax_rate + 100) / 100)), //need to verify calculation1
                        number_format($reservation->fee),
                        $billing->hospital->hospitalPlanByDate($this->endedDate)->contractPlan->plan_name ?? '',
                        (isset($reservation->site_code) && ( $reservation->site_code == 'HP') ) ? 'HPリンク' : '',
                        (isset($reservation->site_code) && ( $reservation->site_code == 'Special') ) ? '○' : '',
                        $reservation->fee_rate . '%',
                    ];

                }

        }

            return collect($row);

    }    

    public function headings(): array
    {
        return [
            // 'hospital Id', sorting testing
            '請求対象月',
            '物件番号',
            '法人名',
            '医療機関名',
            '媒体',
            'サイトコード',
            '来院日',
            'HPリンクステータス',
            '予約コース',
            'オプション有無',
            '総額',
            '税抜き価格', //need to verify calculation1
            '手数料',
            'ROOKプラン',
            'OP',
            '特集ページ',
            '手数料率',
        ];
    }


    /**
     * @return string
     */
    public function title(): string
    {
        return '請求詳細';
    }    
}
