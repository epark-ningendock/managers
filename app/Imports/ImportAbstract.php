<?php


namespace App\Imports;


use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithStartRow;

abstract class ImportAbstract implements WithProgressBar, WithStartRow, WithHeadingRow, WithEvents
{
    use Importable;
    use RegistersEventListeners;

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
}