<?php

namespace App\Exports\Sheets;

use App\Billing;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class BillingDetailSheet implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle
{
    private $dataCollection;

    private $counter = 1;

    public function __construct($collection)
    {
        $this->dataCollection = $collection;
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

            if ( isset($billing->hospital->reservations) )
                
                foreach( $billing->hospital->reservations->sortBy('hospital_id') as $reservation) {

                    if ( $reservation->channel == 2 || $reservation->channel == 3 ) {
                        $channel = 'WEB';
                    } else {
                        $channel = 'TEL';
                    }

                    $row[] = [
                        // $reservation->hospital_id, sorting testing
                        $billing->created_at->format('Y/m'),
                        $billing->hospital->contract_information->property_no,
                        $billing->hospital->contract_information->contractor_name,
                        $billing->hospital->name,
                        $channel,
                        $reservation->completed_date->format('Y/m/d'),
                        $reservation->is_free_hp_link,
                        $billing->contractPlan->plan_name ?? '',
                        isset($reservation->reservation_options) ? '有' : '',
                        'Calculation Errors',
                        $reservation->taxIncludedPrice->tax_rate,

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
            '媒体',
            '来院日',
            'HPリンクステータス',
            '予約コース',
            'オプション有無',
            '総額',
            '税抜き価格',
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
