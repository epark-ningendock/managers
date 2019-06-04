<?php

namespace Tests\Feature;

use App\Enums\StaffStatus;
use App\Staff;
use App\StaffAuth;
use \Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class StaffInputFieldsTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    protected function setUp()
    {
        parent::setUp();
        Session::start();
    }

    public function testRequiredStatus()
    {
        $this->validateFields(['status' => null])->assertSessionHasErrors('status');
    }

    public function testInvalidStatus()
    {
        $this->validateFields(['status' => 100])->assertSessionHasErrors('status');
    }

    public function testRequiredName()
    {
        $this->validateFields(['name' => null])->assertSessionHasErrors('name');
    }

    public function testInvalidName()
    {
        $this->validateFields(['name' => $this->faker->text(100)])->assertSessionHasErrors('name');
    }

    public function testRequiredLoginId()
    {
        $this->validateFields(['login_id' => null])->assertSessionHasErrors('login_id');
    }

    public function testMinLoginId()
    {
        $this->validateFields(['login_id' => $this->faker->text(6)])->assertSessionHasErrors('login_id');
    }

    public function testMaxLoginId()
    {
        $this->validateFields(['login_id' => $this->faker->text(100)])->assertSessionHasErrors('login_id');
    }

    public function testInvalidLoginId()
    {
        $this->validateFields(['login_id' => 'test1234#'])->assertSessionHasErrors('login_id');
    }

    public function testUniqueLoginId()
    {
        $staff = factory(Staff::class)->create();
        $this->validateFields(['login_id' => $staff->login_id])->assertSessionHasErrors('login_id');
    }

    public function testInvalidEmail()
    {
        $this->validateFields(['email' => $this->faker->userName])->assertSessionHasErrors('email');
    }

    public function testUniqueEmail()
    {
        $staff = factory(Staff::class)->create();
        $this->validateFields(['email' => $staff->email])->assertSessionHasErrors('email');
    }

    public function testRequiredIsHospital()
    {
        $this->validateFields(['is_hospital' => null])->assertSessionHasErrors('is_hospital');
    }

    public function testInvalidIsHospital()
    {
        $this->validateFields(['is_hospital' => 9])->assertSessionHasErrors('is_hospital');
    }

    public function testRequiredIsStaff()
    {
        $this->validateFields(['is_staff' => null])->assertSessionHasErrors('is_staff');
    }

    public function testInvalidIsStaff()
    {
        $this->validateFields(['is_staff' => 9])->assertSessionHasErrors('is_staff');
    }

    public function testRequiredIsItemCategory()
    {
        $this->validateFields(['is_item_category' => null])->assertSessionHasErrors('is_item_category');
    }

    public function testInvalidIsItemCategory()
    {
        $this->validateFields(['is_item_category' => 9])->assertSessionHasErrors('is_item_category');
    }

    public function testRequiredIsInvoice()
    {
        $this->validateFields(['is_invoice' => null])->assertSessionHasErrors('is_invoice');
    }

    public function testInvalidIsInvoice()
    {
        $this->validateFields(['is_invoice' => 9])->assertSessionHasErrors('is_invoice');
    }

    public function testRequiredIsPreAccount()
    {
        $this->validateFields(['is_pre_account' => null])->assertSessionHasErrors('is_pre_account');
    }

    public function testInvalidIsPreAccount()
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

    public function testCreateStaff()
    {
        $response = $this->call('POST', 'staff', $this->validFields());
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testUpdateStaffWithoutPassword()
    {
        $staff = factory(Staff::class)->create();
        $params = $this->validFields(['password' => '########']);
        $response = $this->put("/staff/{$staff->id}", $params);
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testUpdateStaffWithPassword()
    {
        $staff = factory(Staff::class)->create();
        $response = $this->put("/staff/{$staff->id}", $this->validFields());
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testUpdatePasswordStaff()
    {
        $staff = factory(Staff::class)->create([
            'authority' => 3,
        ]);
        $attributes =  [
            'password'              => '123456',
            'password_confirmation' => '123456',
            '_token'                 => csrf_token(),
        ] ;

        $response = $this->put("/staff/update-password/{$staff->id}", $attributes);
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testUpdateWithWrongPasswordStaff()
    {
        $staff = factory(Staff::class)->create([
            'authority' => 3,
        ]);
        $attributes =  [
            'password'              => '123456',
            'password_confirmation' => '1234567',
            '_token'                 => csrf_token(),
        ] ;

        $response = $this->put("/staff/update-password/{$staff->id}", $attributes);
        $response->assertSessionHasErrors();
    }

    public function testDeleteStaff()
    {
        $staff = factory(Staff::class)->create();
        $response = $this->call('DELETE', "/staff/$staff->id", ['_token' => csrf_token()]);
        $this->assertEquals(302, $response->getStatusCode());
        $staff = Staff::find($staff->id);
        $this->assertEquals(StaffStatus::Deleted, $staff->status->value);
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
        return array_merge($fields, $overwrites);
    }
}
