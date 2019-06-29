<?php

namespace Tests\Feature;

use App\Staff;
use App\StaffAuth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Support\Facades\Session;
use App\HospitalStaff;

class StaffControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();
        Session::start();

        //authentication
        $hospital_staff = factory(HospitalStaff::class)->create();
        $this->be($hospital_staff);
    }


    /**
     * Test Staff List
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->call('GET', '/staff');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreate()
    {
        $response = $this->call('GET', '/staff/create');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testEdit()
    {
        $staff_auth = factory(StaffAuth::class, 'with_staff')->create();
        $response = $this->call('GET', "/staff/$staff_auth->staff_id/edit");
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testEditPassword()
    {
        $staff = factory(Staff::class)->create([
            'authority' => 3,
        ]);

        $response = $this->get('/staff/edit-password/'. $staff->id);
        $response->assertStatus(200);
    }

    public function testUpdatePassword()
    {
        $staff = factory(Staff::class)->create([
            'authority' => 2,
        ]);

        $response = $this->get('/staff/edit-password/'. $staff->id);
        $this->assertEquals(302, $response->getStatusCode());
    }
}
