<?php

namespace Tests\Feature;

use App\HospitalStaff;
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

        //authentication
        $hospital_staff = factory(HospitalStaff::class)->create();
        $this->be($hospital_staff);
    }

    /**
     * reception list
     *
     * @return void
     */
    public function testReceptionList()
    {
        $response = $this->call('GET', '/reservation');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testInvalidReservationStartDate()
    {
        $response = $this->call('GET', '/reservation?reservation_start_date='.$this->faker->userName);
        $response->assertSessionHasErrors('reservation_start_date');
    }

    public function testInvalidReservationEndDate()
    {
        $response = $this->call('GET', '/reservation?reservation_end_date='.$this->faker->userName);
        $response->assertSessionHasErrors('reservation_end_date');
    }

    public function testInvalidCompletedStartDate()
    {
        $response = $this->call('GET', '/reservation?completed_start_date='.$this->faker->userName);
        $response->assertSessionHasErrors('completed_start_date');
    }

    public function testInvalidCompletedEndDate()
    {
        $response = $this->call('GET', '/reservation?completed_end_date='.$this->faker->userName);
        $response->assertSessionHasErrors('completed_end_date');
    }

    public function testInvalidCustomerNameInReceptionList()
    {
        $response = $this->call('GET', '/reservation?customer_name='.$this->faker->regexify('[A-Za-z]{70}'));
        $response->assertSessionHasErrors('customer_name');
    }

    public function testInvalidStatusInAcceptReservation()
    {
        $reservation = factory(Reservation::class, 'with_all')->create([ 'reservation_status' => ReservationStatus::CANCELLED ]);
        $response = $this->patch('/reservation/'. $reservation->id .'/accept');
        $response->assertSessionHas('errors');
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testAcceptReservation()
    {
        $reservation = factory(Reservation::class, 'with_all')->create([ 'reservation_status' => ReservationStatus::PENDING ]);
        $response = $this->patch('/reservation/'. $reservation->id .'/accept');
        $response->assertSessionHas('success');
        $this->assertEquals(302, $response->getStatusCode());
        $reservation->refresh();
        $this->assertTrue($reservation->reservation_status->is(ReservationStatus::RECEPTION_COMPLETED));
    }

    public function testInvalidStatusInCancelReservation()
    {
        $reservation = factory(Reservation::class, 'with_all')->create([ 'reservation_status' => ReservationStatus::COMPLETED ]);
        $response = $this->delete('/reservation/'. $reservation->id .'/cancel');
        $response->assertSessionHas('errors');
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testCancelReservation()
    {
        $reservation = factory(Reservation::class, 'with_all')->create([ 'reservation_status' => ReservationStatus::PENDING ]);
        $response = $this->delete('/reservation/'. $reservation->id .'/cancel');
        $response->assertSessionHas('success');
        $this->assertEquals(302, $response->getStatusCode());
        $reservation->refresh();
        $this->assertTrue($reservation->reservation_status->is(ReservationStatus::CANCELLED));
    }

    public function testInvalidStatusInCompleteReservation()
    {
        $reservation = factory(Reservation::class, 'with_all')->create([ 'reservation_status' => ReservationStatus::CANCELLED ]);
        $response = $this->patch('/reservation/'. $reservation->id .'/complete');
        $response->assertSessionHas('errors');
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testCompleteReservation()
    {
        $reservation = factory(Reservation::class, 'with_all')->create([ 'reservation_status' => ReservationStatus::RECEPTION_COMPLETED ]);
        $response = $this->patch('/reservation/'. $reservation->id .'/complete');
        $response->assertSessionHas('success');
        $this->assertEquals(302, $response->getStatusCode());
        $reservation->refresh();
        $this->assertTrue($reservation->reservation_status->is(ReservationStatus::COMPLETED));
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




    public function testCreateRoute()
    {
        $response = $this->get('/reservation/create');
        $response->assertStatus(200);
    }

    public function testRegularPriceValidationMax()
    {
        $this->validateReservationCreateFields(['regular_price' => 123456789])->assertSessionHasErrors('regular_price');
    }

    public function validateReservationCreateFields($attributes)
    {

        $this->withExceptionHandling();
        return $this->post('/reservation', $attributes);
    }    

    public function testCreatingReservation()
    {

        $this->post('/reservation', $this->reservationFields());

        request()->merge($this->setDefaultValidForRequestInReservation());

        $reservation = new Reservation();
        $reservation->create(request()->all());

        $this->assertDatabaseHas('reservations', [
            'hospital_id' => auth()->user()->hospital_id
        ]);
    }


    public function setDefaultValidForRequestInReservation()
    {
        return [
            'hospital_id' => auth()->user()->hospital_id,
            'reservation_status' => ReservationStatus::PENDING,
            'terminal_type' => 1,
            'is_repeat' => 0,
            'is_representative' => 0,
            'timezone_pattern_id' => 3233,
            'timezone_id' => 3322,
            'order' => 231,
            'mail_type' => 0,
            'payment_status' => 0,
            'trade_id' => 'mbxrfidstwzvaheonugckljypq',
            'payment_method' => '現金',
        ];
    }


    public function reservationFields($attributes = [])
    {
        return array_merge([
            '_token' => csrf_token(),
            'course_id' => factory('App\Course')->create()->id,
            'reservation_date' => date('Y-m-d'),
            'regular_price' => 3930
        ], $attributes);
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
            $this->assertEquals(ReservationStatus::COMPLETED, $reservation->reservation_status->value);
        }
    }

    protected function validBulkReservationStatusUpdateFields($overwrites = [])
    {
        $reservations = factory(Reservation::class, 'with_all', 5)->create([ 'reservation_status' => ReservationStatus::RECEPTION_COMPLETED ]);
        $ids = $reservations->map(function ($reservation) {
            return $reservation->id;
        })->toArray();
        $fields = [
            'ids' => $ids,
            'reservation_status' => ReservationStatus::COMPLETED,
            '_token' => csrf_token()
        ];
        return array_merge($fields, $overwrites);
    }

}
