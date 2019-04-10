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

	/** @test */
    function it_can_list_page()
    {
	    factory(HospitalStaff::class, 50)->create();
	    $HospitalStaff = HospitalStaff::paginate( 20 );

	    $this->assertEquals(20, $HospitalStaff->count());

    }

	/** @test */
	function it_has_create_page()
	{
		$response = $this->get('/hospital-staff/create');

		$response->assertStatus(200);

	}

	/** @test */
	function it_has_edit_page()
	{
		$hospital_staff = factory(HospitalStaff::class)->create();

		$response = $this->get('/hospital-staff/'. $hospital_staff->id .'/edit');

		$response->assertStatus(200);

	}

}
