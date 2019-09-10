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

    public function __construct($collection)
    {
        $this->collection = $collection;
    }


    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [
			new BillingListingSheet($this->collection),
			new BillingDetailSheet($this->collection),
        ];

        return $sheets;
    }	    
}
