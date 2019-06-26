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

    public function testInvalidStatusInAcceptReservation()
    {
        $reservation = factory(Reservation::class, 'with_all')->create([ 'reservation_status' => ReservationStatus::Cancelled ]);
        $response = $this->patch('/reservation/'. $reservation->id .'/accept');
        $response->assertSessionHas('errors');
        $this->assertEquals(302, $response->getStatusCode());
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

    public function testInvalidStatusInCancelReservation()
    {
        $reservation = factory(Reservation::class, 'with_all')->create([ 'reservation_status' => ReservationStatus::Completed ]);
        $response = $this->delete('/reservation/'. $reservation->id .'/cancel');
        $response->assertSessionHas('errors');
        $this->assertEquals(302, $response->getStatusCode());
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

    public function testInvalidStatusInCompleteReservation()
    {
        $reservation = factory(Reservation::class, 'with_all')->create([ 'reservation_status' => ReservationStatus::Cancelled ]);
        $response = $this->patch('/reservation/'. $reservation->id .'/complete');
        $response->assertSessionHas('errors');
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testCompleteReservation()
    {
        $reservation = factory(Reservation::class, 'with_all')->create([ 'reservation_status' => ReservationStatus::ReceptionCompleted ]);
        $response = $this->patch('/reservation/'. $reservation->id .'/complete');
        $response->assertSessionHas('success');
        $this->assertEquals(302, $response->getStatusCode());
        $reservation->refresh();
        $this->assertTrue($reservation->reservation_status->is(ReservationStatus::Completed));
    }

    public function testRequiredIdsInBulkReservationStatusUpdate()
    {
        $this->validateBulkReservationStatusUpdate(['ids' => null ])->assertSessionHasErrors('ids');
    }

    public function testInvalidIdsInBulkReservationStatusUpdate()
    {
        $this->validateBulkReservationStatusUpdate(['ids' => $this->faker->userName ])->assertSessionHasErrors('ids');
    }

    public function testWrongIdInBulkReservationStatusUpdate()
    {
        $this->validateBulkReservationStatusUpdate(['ids' => [ $this->faker->userName ] ])->assertSessionHasErrors('ids.0');
    }

    public function testRequireReservationStatusInBulkReservationStatusUpdate()
    {
        $this->validateBulkReservationStatusUpdate(['reservation_status' => null ])->assertSessionHasErrors('reservation_status');
    }

    public function testInvalidReservationStatusInBulkReservationStatusUpdate()
    {
        $this->validateBulkReservationStatusUpdate(['reservation_status' => -1 ])->assertSessionHasErrors('reservation_status');
    }

    /**
     * validate fields process for reservation status bulk update
     *
     * @param $attributes
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function validateBulkReservationStatusUpdate($attributes)
    {
        $this->withExceptionHandling();
        return $this->patch('/reception/reservation_status', $this->validBulkReservationStatusUpdateFields($attributes));
    }

    public function testBulkReservationStatusUpdate()
    {
        $params = $this->validBulkReservationStatusUpdateFields();
        $response = $this->patch('/reception/reservation_status', $params);
        $this->assertEquals(302, $response->getStatusCode());
        $response->assertSessionHas('success');
        $reservations = Reservation::whereIn('id', $params['ids'])->get();
        foreach ($reservations as $reservation) {
            $this->assertEquals(ReservationStatus::Completed, $reservation->reservation_status->value);
        }
    }

    protected function validBulkReservationStatusUpdateFields($overwrites = [])
    {
        $reservations = factory(Reservation::class, 'with_all', 5)->create([ 'reservation_status' => ReservationStatus::ReceptionCompleted ]);
        $ids = $reservations->map(function($reservation) { return $reservation->id; })->toArray();
        $fields = [
            'ids' => $ids,
            'reservation_status' => ReservationStatus::Completed,
            '_token' => csrf_token()
        ];
        return array_merge($fields, $overwrites);
    }
}
