<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Hospital;
use App\EmailTemplate;
use \Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EmailTemplateInputFieldsTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    protected function setUp()
    {
        parent::setUp();
        Session::start();
    }

    // public function testRequiredHospitalId()
    // {
    //     $this->validateFields(['hospital_id' => null])->assertSessionHasErrors('hospital_id');
    // }

    public function testRequiredTitle()
    {
        $this->validateFields(['title' => null])->assertSessionHasErrors('title');
    }

    public function testMaxText()
    {
        $this->validateFields(['text' => $this->faker->sentence(20000)])->assertSessionHasErrors('text');
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
        return $this->post('/email-template', $this->validFields($attributes));
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
        $hospital = factory(Hospital::class)->create();
        $email_template = factory(EmailTemplate::class)->raw([
            'hospital_id' => $hospital->id,
        ]);
        $fields = array_merge($email_template);
        $fields['_token'] = csrf_token();
        return array_merge($fields, $overwrites);
    }
}
