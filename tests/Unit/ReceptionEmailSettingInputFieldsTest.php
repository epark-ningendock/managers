<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Hospital;
use App\ReceptionEmailSetting;
use \Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Log;

class ReceptionEmailSettingInputFieldsTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    protected function setUp()
    {
        parent::setUp();
        Session::start();
    }

    public function testRequiredInHospitalEmailReceptionFlg()
    {
        $this->validateFields(['in_hospital_email_reception_flg' => null])->assertSessionHasErrors('in_hospital_email_reception_flg');
    }

    public function testRequiredEmailReceptionFlg()
    {
        $this->validateFields(['email_reception_flg' => null])->assertSessionHasErrors('email_reception_flg');
    }

    public function testInvalidEnum()
    {
        $this->validateFields(['in_hospital_confirmation_email_reception_flg' => $this->faker->numberBetween(2, 50)])->assertSessionHasErrors('in_hospital_confirmation_email_reception_flg');
    }

    public function testInvalidEmail()
    {
        $this->validateFields(['reception_email1' => $this->faker->userName])->assertSessionHasErrors('reception_email1');
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
        $hospital = factory(Hospital::class)->create();
        $reception_email_setting = factory(ReceptionEmailSetting::class)->raw([
            'hospital_id' => $hospital->id,
        ]);
        $this->withExceptionHandling();
        return $this->put("/reception-email-setting/{$hospital->id}", $this->validFields($attributes, $reception_email_setting));
    }
    
    /**
     * Facility Staff fields
     *
     * @param $overwrites
     *
     * @return array
     */
    protected function validFields($overwrites = [], $reception_email_setting)
    {
        $fields = array_merge($reception_email_setting);
        $fields['_token'] = csrf_token();
        return array_merge($fields, $overwrites);
    }
}
