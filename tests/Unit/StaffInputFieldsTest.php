<?php

namespace Tests\Feature;

use App\Enums\Status;
use App\Staff;
use App\StaffAuth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StaffInputFieldsTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    function testRequiredStatus()
    {
        $this->validateFields(['status' => null])->assertSessionHasErrors('status');
    }

    function testInvalidStatus()
    {
        $this->validateFields(['status' => 100])->assertSessionHasErrors('status');
    }

    function testRequiredName()
    {
        $this->validateFields(['name' => null])->assertSessionHasErrors('name');
    }

    function testInvalidName()
    {
        $this->validateFields(['name' => $this->faker->text(100)])->assertSessionHasErrors('name');
    }

    function testRequiredLoginId()
    {
        $this->validateFields(['login_id' => null])->assertSessionHasErrors('login_id');
    }

    function testMinLoginId()
    {
        $this->validateFields(['login_id' => $this->faker->text(6)])->assertSessionHasErrors('login_id');
    }

    function testMaxLoginId()
    {
        $this->validateFields(['login_id' => $this->faker->text(100)])->assertSessionHasErrors('login_id');
    }

    function testInvalidLoginId()
    {
        $this->validateFields(['login_id' => 'test1234#'])->assertSessionHasErrors('login_id');
    }

    function testUniqueLoginId()
    {
        $staff = factory(Staff::class)->create();
        $this->validateFields(['login_id' => $staff->login_id])->assertSessionHasErrors('login_id');
    }

    function testInvalidEmail()
    {
        $this->validateFields(['email' => $this->faker->userName])->assertSessionHasErrors('email');
    }

    function testUniqueEmail()
    {
        $staff = factory(Staff::class)->create();
        $this->validateFields(['email' => $staff->email])->assertSessionHasErrors('email');
    }

    function testRequiredPassword()
    {
        $this->validateFields(['password' => null])->assertSessionHasErrors('password');
    }

    function testMinPassword()
    {
        $this->validateFields(['password' => $this->faker->text(5)])->assertSessionHasErrors('password');
    }

    function testMaxPassword()
    {
        $this->validateFields(['password' => $this->faker->text(100)])->assertSessionHasErrors('password');
    }

    function testInvalidPassword()
    {
        $this->validateFields(['password' => $this->faker->text(50)])->assertSessionHasErrors('password');
    }

    function testRequiredIsHospital()
    {
        $this->validateFields(['is_hospital' => null])->assertSessionHasErrors('is_hospital');
    }

    function testInvalidIsHospital()
    {
        $this->validateFields(['is_hospital' => 9])->assertSessionHasErrors('is_hospital');
    }

    function testRequiredIsStaff()
    {
        $this->validateFields(['is_staff' => null])->assertSessionHasErrors('is_staff');
    }

    function testInvalidIsStaff()
    {
        $this->validateFields(['is_staff' => 9])->assertSessionHasErrors('is_staff');
    }

    function testRequiredIsItemCategory()
    {
        $this->validateFields(['is_item_category' => null])->assertSessionHasErrors('is_item_category');
    }

    function testInvalidIsItemCategory()
    {
        $this->validateFields(['is_item_category' => 9])->assertSessionHasErrors('is_item_category');
    }

    function testRequiredIsInvoice()
    {
        $this->validateFields(['is_invoice' => null])->assertSessionHasErrors('is_invoice');
    }

    function testInvalidIsInvoice()
    {
        $this->validateFields(['is_invoice' => 9])->assertSessionHasErrors('is_invoice');
    }

    function testRequiredIsPreAccount()
    {
        $this->validateFields(['is_pre_account' => null])->assertSessionHasErrors('is_pre_account');
    }

    function testInvalidIsPreAccount()
    {
        $this->validateFields(['is_pre_account' => 9])->assertSessionHasErrors('is_pre_account');
    }

    /**
     * validate fields process
     *
     *
     * @param $attributes
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function validateFields($attributes)
    {
        $this->withExceptionHandling();
        return $this->post('/staff', $this->validFields($attributes));
    }

    function testCreateStaff()
    {
        $response = $this->call('POST', 'staff', $this->validFields());
        $this->assertEquals(302, $response->getStatusCode());
    }

    function testUpdateStaffWithoutPassword() {
        $staff = factory(Staff::class)->create();
        $params = $this->validFields(['password' => '########']);
        $response = $this->put( "/staff/{$staff->id}",  $params);
        $this->assertEquals( 302, $response->getStatusCode() );
    }

    function testUpdateStaffWithPassword() {
        $staff = factory(Staff::class)->create();
        $response = $this->put( "/staff/{$staff->id}",  $this->validFields());
        $this->assertEquals( 302, $response->getStatusCode() );
    }

    public function testDeleteStaff()
    {
        $staff = factory(Staff::class)->create();
        $response = $this->call('DELETE', "/staff/$staff->id", ['_token' => csrf_token()]);
        $this->assertEquals(302, $response->getStatusCode());
        $staff = Staff::find($staff->id);
        $this->assertEquals(Status::Deleted ,$staff->status->value);

    }

    /**
     * Facility Staff fields
     *
     * @param $overwrites
     *
     * @return array
     */
    protected function validFields($overwrites = [])
    {
        $staff = factory(Staff::class)->raw();
        $staff_auth = factory(StaffAuth::class)->raw();
        $fields = array_merge($staff, $staff_auth);
        $fields['_token'] = csrf_token();
        $temp = array_merge($fields, $overwrites);
        return array_merge($fields, $overwrites);
    }
}
