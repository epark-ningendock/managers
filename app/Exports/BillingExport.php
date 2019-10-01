<?php

namespace App\Exports;

use App\Billing;
use App\Exports\Sheets\BillingDetailSheet;
use App\Exports\Sheets\BillingListingSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class BillingExport implements WithMultipleSheets
{
	use Exportable;

    private $collection;

    private $counter = 1;
    private $startedDate;
    private $endedDate;

    public function __construct($collection, $startedDate, $endedDate)
    {
        $this->collection = $collection;
        $this->startedDate = $startedDate;
        $this->endedDate = $endedDate;
    }


    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [
			new BillingListingSheet($this->collection, $this->startedDate, $this->endedDate),
			new BillingDetailSheet($this->collection, $this->startedDate, $this->endedDate),
        ];

        return $sheets;
    }	    
}
