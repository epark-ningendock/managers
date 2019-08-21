<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Hospital;
use App\HospitalEmailSetting;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Session;
use App\HospitalStaff;

class HospitalEmailSettingControllerTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();
        Session::start();

        //authentication
        $hospital_staff = factory(HospitalStaff::class)->create();
        $this->be($hospital_staff);
    }

    public function testIndex()
    {
        $hospital = factory(Hospital::class)->create();
        $hospital_email_setting = factory(HospitalEmailSetting::class)->create([
            'hospital_id' => $hospital->id
        ]);

        $response = $this->call('GET', '/hospital-email-setting');
        $this->assertEquals(200, $response->getStatusCode());
    }
}
