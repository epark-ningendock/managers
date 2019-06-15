<?php

namespace Tests\Feature;

use App\Course;
use App\Customer;
use App\Hospital;
use App\Reservation;
use Tests\TestCase;
use App\Services\ReservationExportService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservationExportServiceTest extends TestCase
{
    /**
     * App\Services\ReservationExportService
     *
     * @return void
     */
    protected $reservation;

    public function testOperationCsv()
    {
        //$export = ReservationExportService('reservation');
        //$this->export = new CalculationService();
        $spyc = new ReservationExportService($this->reservation);
        //$config = $spyc->YAMLLoad($yml_filename);
        dd($spyc->operationCsv());
        $this->assertTrue(true);
    }
}
