<?php

namespace App\Exports\Sheets;

use App\Billing;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class BillingListingSheet implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithTitle
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
        return $this->dataCollection;
    }

    public function map($billing): array
    {
        return [
            $billing->created_at->format('Y/m'),
            $billing->hospital->contract_information->property_no,
            $billing->hospital->contract_information->contractor_name,
            $billing->hospital->name,
            $billing->hospital->reservations()->whereMonth('created_at', now()->month)->get()->pluck('fee')->sum(),
            'Consumption tax subtotal',
            $billing->hospital->reservations()->whereMonth('created_at', now()->month)->get()->pluck('fee')->sum() + $billing->contractPlan->fee_rate
        ];
    }    

    public function headings(): array
    {
        return [
            '請求対象月',
            '物件番号',
            '法人名',
            '医療機関名',
            '請求金額小計',
            '消費税小計',
            '請求金額合計'
        ];
    }


    /**
     * @return string
     */
    public function title(): string
    {
        return '顧客請求対象_'. $this->collection()->first()->created_at->format('Ym');
    }    
}
