<?php

namespace Tests\Feature;

use App\Hospital;
use App\HospitalStaff;
use App\Option;
use App\TaxClass;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OptionControllerTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    protected function setUp()
    {
        parent::setUp();
        Session::start();
    }

    public function withLoginUser()
    {
        $hospitalStaff = factory(HospitalStaff::class)->create();
        Auth::login($hospitalStaff); //You should be logged in :)
    }


    public function testOptionListing()
    {
        $response = $this->call('GET', 'option');
        $this->assertEquals(200, $response->getStatusCode());
    }

//
    //	public function testOptionCreate() {
    //		$this->withLoginUser();
    //		$response = $this->call( 'GET', 'option/create' );
    //		$this->assertEquals( 200, $response->getStatusCode() );
    //	}
//
//
    //	public function testEmptyName() {
    //		$this->validateFields( [ 'name' => '' ] )->assertSessionHasErrors( 'name' );
    //	}
//
    //	public function testNameFieldWordLimit() {
    //		$this->validateFields( [ 'name' => $this->faker->paragraph(10) ] )->assertSessionHasErrors( 'name' );
    //	}
//
    //	public function testConfirmFieldWordLimit() {
    //		$this->validateFields( [ 'confirm' => $this->faker->paragraph(100) ] )->assertSessionHasErrors( 'confirm' );
    //	}
//
    //	public function testEmptyPrice() {
    //		$this->validateFields( [ 'price' => '' ] )->assertSessionHasErrors( 'price' );
    //	}
//
    //	public function testPriceFieldNumber() {
    //		$this->validateFields( [ 'price' => 888888888 ] )->assertSessionHasErrors( 'price' );
    //	}
//
    //	public function testTaxClassIdFieldRequiredCheck() {
    //		$this->validateFields( [ 'tax_class_id' => '' ] )->assertSessionHasErrors( 'tax_class_id' );
    //	}


    protected function validateFields($attributes)
    {
        $this->withExceptionHandling();

        return $this->post('/option', $this->validFields($attributes));
    }

    /**
     * Option Fields
     */
    protected function validFields($overwrites = [])
    {
        return array_merge([
            'hospital_id'  => 1,
            'name'         => 'Option Name',
            'confirm'      => null,
            'price'        => 10000,
            'tax_class_id' => factory(TaxClass::class)->create()->id,
            'order'        => 1,
            'status'       => 1,
            '_token' => csrf_token(),
        ], $overwrites);
    }
}
