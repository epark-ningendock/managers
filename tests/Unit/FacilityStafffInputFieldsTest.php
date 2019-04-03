<?php

namespace Tests\Unit;

use App\FacilityStaff;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FacilityStafffInputFieldsTest extends TestCase {
	use DatabaseMigrations;

	/** @test */
	function it_required_name() {
		$this->it_validate_field( [ 'name' => '' ] )->assertSessionHasErrors( 'name' );
	}

	/** @test */
	function it_required_email() {
		$this->it_validate_field( [ 'email' => '' ] )->assertSessionHasErrors( 'email' );
	}

	/** @test */
	function it_required_valid_email() {
		$this->it_validate_field( [ 'email' => 'dsfsdf' ] )->assertSessionHasErrors( 'email' );
	}

	/** @test */
	function it_required_login_id() {
		$this->it_validate_field( [ 'login_id' => '' ] )->assertSessionHasErrors( 'login_id' );
	}

	/** @test */
	function it_required_password() {
		$this->it_validate_field( [ 'password' => '' ] )->assertSessionHasErrors( 'password' );
	}

	/** @test */
	function it_can_create_facility_staff() {

		$response = $this->call( 'POST', 'facility-staff', $this->validFields() );

		$this->assertEquals( 302, $response->getStatusCode() );

	}

	/** @test */
	function it_can_update_facility_staff() {

		$facility_staff = factory( FacilityStaff::class )->create();

		$attributes =  [
			'id'       => $facility_staff->id,
			'name'     => 'john',
			'email'    => 'john@mail.com',
			'login_id' => 'f93kffhfu',
			'password' => bcrypt( '123456' ),
			'_token'   => csrf_token(),
		] ;

		$response = $this->put( "/facility-staff/{$facility_staff->id}", $attributes );
		$this->assertEquals( 302, $response->getStatusCode() );

	}

	/** @test */
	public function it_can_delete_facility_staff() {

		$facility_staff = factory( FacilityStaff::class )->create();

		$response = $this->call( 'DELETE', '/facility-staff/' . $facility_staff->id, [ '_token' => csrf_token() ] );
		$this->assertEquals( 302, $response->getStatusCode() );
		$this->assertDatabaseMissing( 'facility_staffs', [ 'id' => $facility_staff->id ] );
	}


	/**
	 * validate fields process
	 *
	 * @param $attributes
	 *
	 * @return \Illuminate\Foundation\Testing\TestResponse
	 */
	protected function it_validate_field( $attributes ) {
		$this->withExceptionHandling();

		return $this->post( '/facility-staff', $this->validFields( $attributes ) );
	}


	/**
	 * Facility Staff fields
	 *
	 * @param $overwrites
	 *
	 * @return array
	 */
	protected function validFields( $overwrites = [] ) {
		return array_merge( [
			'name'     => 'john',
			'email'    => 'john@mail.com',
			'login_id' => 'f93kffhfu',
			'password' => bcrypt( '123456' ),
			'_token'   => csrf_token(),
		], $overwrites );
	}

}
