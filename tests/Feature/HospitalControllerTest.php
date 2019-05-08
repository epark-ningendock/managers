<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HospitalControllerTest extends TestCase
{
	use DatabaseMigrations, RefreshDatabase;

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
}
