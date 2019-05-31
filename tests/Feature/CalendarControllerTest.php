<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Course;
use App\Calendar;
use Illuminate\Support\Facades\Session;

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

    function testCreateCalendar()
    {
        $response = $this->call('POST', 'calendar', $this->validFields());
        $response->assertSessionHas('success');
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testUpdateCalendar() {
        $calendar = factory(Calendar::class)->create();

        $response = $this->put( "/calendar/{$calendar->id}", $this->validFields());
        $response->assertSessionHas('success');
        $this->assertEquals( 302, $response->getStatusCode() );
    }

    function testRequiredName()
    {
        $this->validateFields([ 'name' => null])->assertSessionHasErrors('name');
    }

    function testRequiredIsCalendarDisplay()
    {
        $this->validateFields([ 'is_calendar_display' => null])->assertSessionHasErrors('is_calendar_display');
    }

    function testInvalidIsCalendarDisplay()
    {
        $this->validateFields(['is_calendar_display' => -1])->assertSessionHasErrors('is_calendar_display');
    }

    function testInvalidRegisteredCalendarIds()
    {
        $this->validateFields([ 'registered_course_ids' => $this->faker->userName ])->assertSessionHasErrors('registered_course_ids');
    }

    function testInvalidRegisteredCalendarIdValues()
    {
        $this->validateFields([ 'registered_course_ids' => [ $this->faker->userName ] ])->assertSessionHasErrors('registered_course_ids.0');
    }

    function testInvalidUnRegisteredCalendarIds()
    {
        $this->validateFields([ 'unregistered_course_ids' => $this->faker->userName ])->assertSessionHasErrors('unregistered_course_ids');
    }

    function testInvalidUnRegisteredCalendarIdValues()
    {
        $this->validateFields([ 'unregistered_course_ids' => [ $this->faker->userName ] ])->assertSessionHasErrors('unregistered_course_ids.0');
    }

    /**
     * validate fields process
     *
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
        $register_course_ids = factory(Course::class, 5)->create()->map(function($c){ return $c->id; });
        $unregister_course_ids = factory(Course::class, 5)->create()->map(function($c){ return $c->id; });
        $fields = factory(Calendar::class)->raw();
        $fields['registered_course_ids'] = $register_course_ids->toArray();
        $fields['unregistered_course_ids'] = $unregister_course_ids->toArray();
        $fields['_token'] = csrf_token();
        return array_merge($fields, $overwrites);
    }
}
