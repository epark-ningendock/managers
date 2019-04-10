<?php

namespace Tests\Unit;

use App\HospitalStaff;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;


class HospitalStaffControllerTest extends TestCase
{
	use DatabaseMigrations, RefreshDatabase;

    function testItCanListPage()
    {
	    factory(HospitalStaff::class, 50)->create();
	    $HospitalStaff = HospitalStaff::paginate( 20 );

	    $this->assertEquals(20, $HospitalStaff->count());

    }


	function testItHasCreatePage()
	{
		$response = $this->get('/hospital-staff/create');

		$response->assertStatus(200);

	}

	function testItHasEditPage()
	{
		$hospital_staff = factory(HospitalStaff::class)->create();

		$response = $this->get('/hospital-staff/'. $hospital_staff->id .'/edit');

		$response->assertStatus(200);

	}

}
