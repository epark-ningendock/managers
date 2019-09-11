<?php

namespace Feature;

use App\Hospital;
use App\HospitalStaff;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HospitalControllerTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase, WithFaker;


    public function setUp()
    {
        parent::setUp();
        $this->hospitalStaffSignIn();
    }

    public function testStoreHospital()
    {
        $attributes = $this->attributes([
            'name' => 'Nora Hospital'
        ]);

        $this->post('/hospital', $attributes);
        $this->assertDatabaseHas('hospitals', [
            'name' => 'Nora Hospital'
        ]);
    }

    public function testListingPage()
    {
        $response = $this->get('hospital');
        $response->assertStatus(200);
    }

    public function testSearch()
    {
        $response = $this->get('hospital/search');
        $response->assertStatus(200);
    }

    public function testSearchText()
    {
        $response = $this->get('hospital/search/text');
        $response->assertStatus(200);
    }


    public function testCreateHospitalPage()
    {
        $response = $this->get('hospital/create');
        $response->assertStatus(200);
    }


    protected function attributes($attributes = [])
    {
        return array_merge([
            'name'                        => 'Hospital ' . $this->faker->company,
            'kana'                        => $this->faker->randomElement([ 'asc', 'desc' ]),
            'postcode'                    => $this->faker->postcode,
            'district_code_id'            => $this->faker->randomNumber(),
            'address1'                    => $this->faker->address,
            'address2'                    => $this->faker->address,
            'longitude'                   => $this->faker->longitude,
            'latitude'                    => $this->faker->latitude,
            'direction'                   => $this->faker->randomNumber(),
            'streetview_url'              => $this->faker->url,
            'tel'                         => $this->faker->phoneNumber,
            'paycall'                     => $this->faker->phoneNumber,
            'fax'                         => $this->faker->phoneNumber,
            'url'                         => $this->faker->url,
            'consultation_note'           => $this->faker->text(),
            'memo'                        => $this->faker->text(100),
            'medical_examination_system_id'              => rand(1, 100),
            'rail1'                       => $this->faker->randomNumber(),
            'station1'                    => $this->faker->randomNumber(),
            'access1'                     => $this->faker->randomElement([
                'gate No1',
                'entrance 2',
                'platform 4',
                'gate 9',
                'Station 1',
            ]),
            'rail2'                       => $this->faker->randomNumber(),
            'station2'                    => $this->faker->randomNumber(),
            'access2'                     => $this->faker->randomElement([
                'gate No1',
                'entrance 4',
                'platform 5',
                'gate 8',
                'Station 1',
            ]),
            'rail3'                       => $this->faker->randomNumber(),
            'station3'                    => $this->faker->randomNumber(),
            'access3'                     => $this->faker->randomElement([
                'gate No 4',
                'gate 11',
                'entrance 4',
                'entrance 8',
                'platform 4',
                'gate 9',
                'Station 11',
            ]),
            'rail4'                       => $this->faker->randomNumber(),
            'station4'                    => $this->faker->randomNumber(),
            'access4'                     => $this->faker->randomElement([
                'gate No 4',
                'entrance 8',
                'platform 4',
                'gate 9',
                'Station 11',
            ]),
            'rail5'                       => $this->faker->randomNumber(),
            'station5'                    => $this->faker->randomNumber(),
            'access5'                     => $this->faker->randomElement([
                'gate No 4',
                'entrance 8',
                'platform 4',
                'gate 9',
                'Station 11',
            ]),
            'memo1'                       => $this->faker->text,
            'memo2'                       => $this->faker->text,
            'memo3'                       => $this->faker->text,
            'principal'                   => $this->faker->name,
            'principal_history'           => $this->faker->text,
            'pv_count'                    => $this->faker->numberBetween(0, 1),
            'pvad'                        => $this->faker->numberBetween(0, 1),
            'is_pickup'                   => $this->faker->numberBetween(0, 1),
            'hospital_staff_id'           => 1,
            'status'                      => $this->faker->randomElement([ '0', '1', 'X' ]),
            'free_area'                   => $this->faker->text,
            'search_word'                 => $this->faker->text,
            'plan_code'                   => $this->faker->numberBetween(1, 9),
            'hplink_contract_type'        => $this->faker->numberBetween(0, 2),
            'hplink_count'                => $this->faker->randomNumber(),
            'hplink_price'                => $this->faker->randomNumber(),
            'is_pre_account'             => $this->faker->numberBetween(0, 1),
            'pre_account_discount_rate'   => $this->faker->randomNumber(),
            'pre_account_commission_rate' => $this->faker->randomElement([ '0.5', '1.9', '3.9', '4.6' ]),
        ], $attributes);
    }
}
