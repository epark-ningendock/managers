<?php

namespace Tests\Unit;

use App\FacilityStaff;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;


class FacilityStaffControllerTest extends TestCase
{
	use DatabaseMigrations, RefreshDatabase;

	/** @test */
    function it_can_list_page()
    {
	    factory(FacilityStaff::class, 50)->create();
	    $FacilityStaff = FacilityStaff::paginate( 20 );

	    $this->assertEquals(20, $FacilityStaff->count());

    }

	/** @test */
	function it_has_create_page()
	{
		$response = $this->get('/facility-staff/create');

		$response->assertStatus(200);

	}

	/** @test */
	function it_has_edit_page()
	{
		$facility_staff = factory(FacilityStaff::class)->create();

		$response = $this->get('/facility-staff/'. $facility_staff->id .'/edit');

		$response->assertStatus(200);

	}

}
