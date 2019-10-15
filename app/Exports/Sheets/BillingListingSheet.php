<?php

namespace App\Exports\Sheets;

use App\Billing;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\TaxClass;

class BillingListingSheet implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithTitle
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
        return $this->dataCollection;
    }

    public function map($billing): array
    {
        $taxIncludePrice = $billing->hospital->hospitalPlanByDate($this->endedDate)->contractPlan->monthly_contract_fee + 
            $billing->hospital->reservationByCompletedDate($this->startedDate, $this->endedDate)->pluck('fee')->sum();
        $taxExcludePrice = $taxIncludePrice / TaxClass::TEN_PERCENT;

        return [
            $this->endedDate->format('m'),
            $billing->hospital->contract_information->property_no,
            $billing->hospital->contract_information->contractor_name,
            $billing->hospital->name,
            number_format(floor($taxExcludePrice)),
            number_format(floor($taxIncludePrice) - floor($taxExcludePrice)),
            number_format(floor($taxIncludePrice))
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
        return '請求金額';
    }    
}
