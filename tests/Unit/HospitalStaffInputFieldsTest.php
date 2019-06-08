<?php

namespace Tests\Unit;

use App\HospitalStaff;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HospitalStaffInputFieldsTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();
        Session::start();
    }

    public function testItRequiredName()
    {
        $this->itValidateField([ 'name' => '' ])->assertSessionHasErrors('name');
    }

    public function testItRequiredEmail()
    {
        $this->itValidateField([ 'email' => '' ])->assertSessionHasErrors('email');
    }

    public function testItRequiredValidEmail()
    {
        $this->itValidateField([ 'email' => 'dsfsdf' ])->assertSessionHasErrors('email');
    }

    public function testItRequiredLoginId()
    {
        $this->itValidateField([ 'login_id' => '' ])->assertSessionHasErrors('login_id');
    }

    // function testItRequiredPassword() {
    // 	$this->itValidateField( [ 'password' => '' ] )->assertSessionHasErrors( 'password' );
    // }

    /**
     * validate fields process
     *
     * @param $attributes
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function itValidateField($attributes)
    {
        $this->withExceptionHandling();

        return $this->post('/hospital-staff', $this->validFields($attributes));
    }

    public function testItCanCreateHospitalStaff()
    {
        $response = $this->call('POST', 'hospital-staff', $this->validFields());

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testItCanUpdateHospitalStaff()
    {
        $hospital_staff = factory(HospitalStaff::class)->create();

        $attributes =  [
            'id'       => $hospital_staff->id,
            'name'     => 'john',
            'email'    => 'john@mail.com',
            'login_id' => 'f93kffhfu',
            'password' => bcrypt('123456'),
            '_token'   => csrf_token(),
        ] ;

        $response = $this->put("/hospital-staff/{$hospital_staff->id}", $attributes);
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testItCanDeleteHospitalStaff()
    {
        $hospital_staff = factory(HospitalStaff::class)->create();

        $response = $this->call('DELETE', '/hospital-staff/' . $hospital_staff->id, [ '_token' => csrf_token() ]);
        //		$this->assertEquals( 302, $response->getStatusCode() );
        $this->assertSoftDeleted('hospital_staffs', ['id' => $hospital_staff->id,'email' => $hospital_staff->email, 'login_id' => $hospital_staff->login_id]);
    }


    /**
     * Facility Staff fields
     *
     * @param $overwrites
     *
     * @return array
     */
    protected function validFields($overwrites = [])
    {
        return array_merge([
            'name'     => 'john',
            'email'    => 'john@mail.com',
            'login_id' => 'f93kffhfu',
            'password' => bcrypt('123456'),
            'hospital_id' => 1,
            'reset_token_digest' => '$2y$10$TKh8H1.PfQx37YgCzwi',
            'reset_sent_at' => $faker->dateTime,
            '_token'   => csrf_token(),
        ], $overwrites);
    }
}
