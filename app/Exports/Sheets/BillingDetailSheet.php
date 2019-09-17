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

    public function __construct($collection, $startedDate, $endedDate)
    {
        $this->dataCollection = $collection;
        $this->startedDate = $startedDate;
        $this->endedDate = $endedDate;
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

            $reservations = $billing->hospital->reservations->sortBy('hospital_id');

            if ( isset($reservations) )

                foreach( $reservations as $key => $reservation) {

                    if ( count($reservations) == ($key+1) ) {
                        $channel = $billing->contractPlan->monthly_contract_fee;
                    } else {

                        if ( $reservation->channel == 2 || $reservation->channel == 3 ) {
                            $channel = 'WEB';
                        } else {
                            $channel = 'TEL';
                        }

                    }

                    $hp_link_status = '';
                    if ( ($reservation->site_code == 'HP') && ( $reservation->fee == 0) && ( ReservationStatus::getKey($reservation->reservation_status) == 4) ) {
                        $hp_link_status = 'HP Link';
                    } elseif ( ($reservation->site_code == 'HP') && ( $reservation->fee == 0) ) {
                        $hp_link_status = 'HP Link (Cancel)';
                    }


                    $the_amount = $reservation->tax_included_price + $reservation->adjustment_price + $reservation->reservation_options->pluck('option_price')->sum();



                    $row[] = [
                        // $reservation->hospital_id, sorting testing
                        $billing->created_at->format('Y/m'),
                        $billing->hospital->contract_information->property_no,
                        $billing->hospital->contract_information->contractor_name,
                        $billing->hospital->name,
                        $channel,
                        $reservation->completed_date->format('Y/m/d'),
                        $hp_link_status,
                        $billing->contractPlan->plan_name ?? '',
                        isset($reservation->reservation_options) ? '有' : '',
                        number_format($the_amount),
                        $the_amount / $reservation->tax_rate, //need to verify calculation1
                        number_format($reservation->fee),
                        $billing->contractPlan->plan_name ?? '',
                        (isset($reservation->site_code) && ( $reservation->site_code == 'HP') ) ? 'HPリンク' : '',
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
            '来院日',
            'HPリンクステータス',
            '予約コース',
            'オプション有無',
            '総額',
            '税抜き価格', //need to verify calculation1
            '手数料_税抜',
            'ROOKプラン',
            'OP',
            '手数料料率',
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
