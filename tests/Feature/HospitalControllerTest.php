<?php

namespace Feature;


use App\HospitalStaff;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HospitalControllerTest extends TestCase
{
	use DatabaseMigrations, RefreshDatabase;


	public function setUp()
	{
		parent::setUp();
		$this->hospitalStaffSignIn();
	}

	public function testListingPage() {
		$response = $this->get('hospital');
		$response->assertStatus(200);
    }

	public function testSearch() {
		$response = $this->get('hospital/search');
		$response->assertStatus(200);
    }

	public function testSearchText() {
		$response = $this->get('hospital/search/text');
		$response->assertStatus(200);
	}


	public function testCreateHospitalPage()
	{
		$response = $this->get('hospital/create');
		$response->assertStatus(200);
	}
}
