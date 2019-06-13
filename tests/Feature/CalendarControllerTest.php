<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Course;
use App\Calendar;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class CalendarControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp()
    {
        parent::setUp();
        Session::start();
    }

    /**
     * Test Calendar List
     * @return void
     */
    public function testIndex()
    {
        $response = $this->call('GET', '/calendar');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreate()
    {
        $response = $this->call('GET', '/calendar/create');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testEdit()
    {
        $calendar = factory(Calendar::class)->create();
        $response = $this->call('GET', '/calendar/'.$calendar->id.'/edit');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateCalendar()
    {
        $response = $this->call('POST', 'calendar', $this->validFields());
        $response->assertSessionHas('success');
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testUpdateCalendar()
    {
        $calendar = factory(Calendar::class)->create();

        $response = $this->put("/calendar/{$calendar->id}", $this->validFields());
        $response->assertSessionHas('success');
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testRequiredName()
    {
        $this->validateFields([ 'name' => null])->assertSessionHasErrors('name');
    }

    public function testRequiredIsCalendarDisplay()
    {
        $this->validateFields([ 'is_calendar_display' => null])->assertSessionHasErrors('is_calendar_display');
    }

    public function testInvalidIsCalendarDisplay()
    {
        $this->validateFields(['is_calendar_display' => -1])->assertSessionHasErrors('is_calendar_display');
    }

    public function testInvalidRegisteredCalendarIds()
    {
        $this->validateFields([ 'registered_course_ids' => $this->faker->userName ])->assertSessionHasErrors('registered_course_ids');
    }

    public function testInvalidRegisteredCalendarIdValues()
    {
        $this->validateFields([ 'registered_course_ids' => [ $this->faker->userName ] ])->assertSessionHasErrors('registered_course_ids.0');
    }

    public function testInvalidUnRegisteredCalendarIds()
    {
        $this->validateFields([ 'unregistered_course_ids' => $this->faker->userName ])->assertSessionHasErrors('unregistered_course_ids');
    }

    public function testInvalidUnRegisteredCalendarIdValues()
    {
        $this->validateFields([ 'unregistered_course_ids' => [ $this->faker->userName ] ])->assertSessionHasErrors('unregistered_course_ids.0');
    }

    public function testCalendarSetting()
    {
        $calendar = factory(Calendar::class)->create();
        $response = $this->call('GET', '/calendar/'.$calendar->id.'/setting');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testInvalidDaysInCalendarSetting()
    {
        $this->validateSettingFields(['days' => $this->faker->userName ])->assertSessionHasErrors('days');
    }

    public function testWrongDayFormatInCalendarSetting()
    {
        $this->validateSettingFields(['days' => [ $this->faker->userName ] ])->assertSessionHasErrors('days.0');
    }

    public function testInvalidIsReservationAcceptancesInCalendarSetting()
    {
        $this->validateSettingFields(['is_reservation_acceptances' => $this->faker->userName ])->assertSessionHasErrors('is_reservation_acceptances');
    }

    public function testWrongIsReservationAcceptancesInCalendarSetting()
    {
        $this->validateSettingFields(['is_reservation_acceptances' => [ $this->faker->userName ] ])->assertSessionHasErrors('is_reservation_acceptances.0');
    }

    public function testInvalidReservationFramesInCalendarSetting()
    {
        $this->validateSettingFields(['reservation_frames' => $this->faker->userName ])->assertSessionHasErrors('reservation_frames');
    }

    public function testWrongReservationFramesInCalendarSetting()
    {
        $this->validateSettingFields(['reservation_frames' => [ $this->faker->userName ] ])->assertSessionHasErrors('reservation_frames.0');
    }

    public function testUpdateCalendarSetting()
    {
        $calendar = factory(Calendar::class)->create();
        $response = $this->patch('/calendar/'.$calendar->id.'/setting', $this->validSettingFields());
        $response->assertSessionHas('success');
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testHolidaySetting()
    {
        $response = $this->call('get', '/calendar/holiday');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testInvalidDaysInHolidaySetting()
    {
        $this->validateHolidayFields(['days' => $this->faker->userName ])->assertSessionHasErrors('days');
    }

    public function testWrongDayFormatInHolidaySetting()
    {
        $this->validateHolidayFields(['days' => [ $this->faker->userName ] ])->assertSessionHasErrors('days.0');
    }

    public function testInvalidIsHolidaysInHolidaySetting()
    {
        $this->validateHolidayFields(['is_holidays' => $this->faker->userName ])->assertSessionHasErrors('is_holidays');
    }

    public function testWrongIsHolidaysInHolidaySetting()
    {
        $this->validateHolidayFields(['is_holidays' => [ $this->faker->userName ] ])->assertSessionHasErrors('is_holidays.0');
    }

    public function testUpdateHolidaySetting()
    {
        $response = $this->patch('/calendar/holiday', $this->validHolidayFields());
        $response->assertSessionHas('success');
        $this->assertEquals(302, $response->getStatusCode());
    }

    /**
     * validate holiday setting fields process
     *
     * @param $attributes
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function validateHolidayFields($attributes)
    {
        $this->withExceptionHandling();
        return $this->patch('/calendar/holiday', $this->validHolidayFields($attributes));
    }

    /**
     * validate setting fields process
     *
     * @param $attributes
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function validateSettingFields($attributes)
    {
        $calendar = factory(Calendar::class)->create();
        $this->withExceptionHandling();
        return $this->patch('/calendar/'.$calendar->id.'/setting', $this->validSettingFields($attributes));
    }


    /**
     * validate fields process
     *
     * @param $attributes
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function validateFields($attributes)
    {
        $this->withExceptionHandling();
        return $this->post('/calendar', $this->validFields($attributes));
    }

    /**
     * Calendar fields
     *
     * @param $overwrites
     *
     * @return array
     */
    protected function validFields($overwrites = [])
    {
        $register_course_ids = factory(Course::class, 5)->create()->map(function ($c) {
            return $c->id;
        });
        $unregister_course_ids = factory(Course::class, 5)->create()->map(function ($c) {
            return $c->id;
        });
        $fields = factory(Calendar::class)->raw();
        $fields['registered_course_ids'] = $register_course_ids->toArray();
        $fields['unregistered_course_ids'] = $unregister_course_ids->toArray();
        $fields['_token'] = csrf_token();
        return array_merge($fields, $overwrites);
    }

    /**
     * Calendar setting fields
     * @param array $overwrites
     * @return array
     */
    protected function validSettingFields($overwrites = [])
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->addMonth(5)->endOfMonth();
        $days = collect();
        $is_reservation_acceptances = collect();
        $reservation_frames = collect();
        while($start->lt($end)) {
            $days->push($start->format('Ymd'));
            $is_reservation_acceptances->push($this->faker->randomElement([0, 1]));
            $reservation_frames->push($this->faker->randomElement(range(0, 99)));
            $start->addDay(1);
        }
        $fields = [
            'days' => $days->toArray(),
            'is_reservation_acceptances' => $is_reservation_acceptances->toArray(),
            'reservation_frames' => $reservation_frames->toArray(),
            '_token' => csrf_token()
            ];
        return array_merge($fields, $overwrites);
    }

    /**
     * Holiday setting fields
     * @param array $overwrites
     * @return array
     */
    protected function validHolidayFields($overwrites = [])
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->addMonth(11)->endOfMonth();
        $days = collect();
        $is_holidays = collect();
        while($start->lt($end)) {
            $days->push($start->format('Ymd'));
            $is_holidays->push($this->faker->randomElement(['', '1']));
            $start->addDay(1);
        }
        $fields = [
            'days' => $days->toArray(),
            'is_holidays' => $is_holidays->toArray(),
            '_token' => csrf_token()
        ];
        return array_merge($fields, $overwrites);
    }
}
