<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Enums\ReservationStatus;
use Illuminate\Support\Facades\Session;
use App\Reservation;

class ReservationControllerTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    protected function setUp()
    {
        parent::setUp();
        Session::start();
    }

    /**
     * reception list
     *
     * @return void
     */
    public function testReceptionList()
    {
        $response = $this->call('GET', '/reception');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testInvalidReservationStartDate()
    {
        $response = $this->call('GET', '/reception?reservation_start_date='.$this->faker->userName);
        $response->assertSessionHasErrors('reservation_start_date');
    }

    public function testInvalidReservationEndDate()
    {
        $response = $this->call('GET', '/reception?reservation_end_date='.$this->faker->userName);
        $response->assertSessionHasErrors('reservation_end_date');
    }

    public function testInvalidCompletedStartDate()
    {
        $response = $this->call('GET', '/reception?completed_start_date='.$this->faker->userName);
        $response->assertSessionHasErrors('completed_start_date');
    }

    public function testInvalidCompletedEndDate()
    {
        $response = $this->call('GET', '/reception?completed_end_date='.$this->faker->userName);
        $response->assertSessionHasErrors('completed_end_date');
    }

    public function testAcceptReservation()
    {
        $reservation = factory(Reservation::class, 'with_all')->create([ 'reservation_status' => ReservationStatus::Pending ]);
        $response = $this->patch('/reservation/'. $reservation->id .'/accept');
        $response->assertSessionHas('success');
        $this->assertEquals(302, $response->getStatusCode());
        $reservation->refresh();
        $this->assertTrue($reservation->reservation_status->is(ReservationStatus::ReceptionCompleted));
    }

    public function testCancelReservation()
    {
        $reservation = factory(Reservation::class, 'with_all')->create([ 'reservation_status' => ReservationStatus::Pending ]);
        $response = $this->delete('/reservation/'. $reservation->id .'/cancel');
        $response->assertSessionHas('success');
        $this->assertEquals(302, $response->getStatusCode());
        $reservation->refresh();
        $this->assertTrue($reservation->reservation_status->is(ReservationStatus::Cancelled));
    }
}
