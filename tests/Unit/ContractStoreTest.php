<?php

namespace Tests\Unit;

use App\ContractInformation;
use App\Hospital;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContractStoreTest extends TestCase
{
    use  DatabaseMigrations, RefreshDatabase, WithFaker;


    protected function setUp()
    {
        parent::setUp();
        Session::start();
    }

    public function slug($slug)
    {
        return '/contract-information' . $slug;
    }

    public function testItRequiredMedicalInstitutionName()
    {
        $this->itValidateField([ 'medical_institution_name' => '' ])->assertSessionHasErrors('medical_institution_name');
    }

    public function testContractorNameKana()
    {
        $this->itValidateField([ 'contractor_name_kana' => '' ])->assertSessionHasErrors('contractor_name_kana');
    }

    public function testContractorNameKanaMax100()
    {
        $this->itValidateField([ 'contractor_name_kana' => $this->faker->text(300) ])->assertSessionHasErrors('contractor_name_kana');
    }

    public function testContractorName()
    {
        $this->itValidateField([ 'contractor_name' => '' ])->assertSessionHasErrors('contractor_name');
    }

    public function testContractorNameMax100()
    {
        $this->itValidateField([ 'contractor_name' => $this->faker->text(300) ])->assertSessionHasErrors('contractor_name');
    }

    public function testApplicationDateRequired()
    {
        $this->itValidateField([ 'application_date' => '' ])->assertSessionHasErrors('application_date');
    }

    public function testApplicationDateValid()
    {
        $this->itValidateField([ 'application_date' => 'dfsdf' ])->assertSessionHasErrors('application_date');
    }

    public function testBillingStartDateRequired()
    {
        $this->itValidateField([ 'billing_start_date' => '' ])->assertSessionHasErrors('billing_start_date');
    }

    public function testBillingStartDateValid()
    {
        $this->itValidateField([ 'billing_start_date' => 'sdf' ])->assertSessionHasErrors('billing_start_date');
    }

    public function testCancellationDateRequired()
    {
        $this->itValidateField([ 'cancellation_date' => '' ])->assertSessionHasErrors('cancellation_date');
    }

    public function testCancellationDateValid()
    {
        $this->itValidateField([ 'cancellation_date' => 'sfsdf' ])->assertSessionHasErrors('cancellation_date');
    }

    public function testCancellationDateIsFuture()
    {
        $now = now()->getTimestamp() - 10000000;
        $this->itValidateField([ 'cancellation_date' => $this->faker->date('m/d/Y', $now) ])->assertSessionHasErrors('cancellation_date');
    }

    public function testRepresentativeNameKanaRequired()
    {
        $this->itValidateField([ 'representative_name_kana' => '' ])->assertSessionHasErrors('representative_name_kana');
    }

    public function testRepresentativeNameKanaMax100()
    {
        $this->itValidateField([ 'representative_name_kana' => $this->faker->text(300) ])->assertSessionHasErrors('representative_name_kana');
    }

    public function testRepresentativeNameRequired()
    {
        $this->itValidateField([ 'representative_name' => '' ])->assertSessionHasErrors('representative_name');
    }

    public function testRepresentativeNameMax100()
    {
        $this->itValidateField([ 'representative_name' => $this->faker->text(300) ])->assertSessionHasErrors('representative_name');
    }

    public function testPostcodeRequired()
    {
        $this->itValidateField([ 'postcode' => '' ])->assertSessionHasErrors('postcode');
    }

    public function testPostcodeNumberDashCheck()
    {
        $this->itValidateField([ 'postcode' => 'sdf sfdsf' ])->assertSessionHasErrors('postcode');
    }


    public function testAddressRequired()
    {
        $this->itValidateField([ 'address' => '' ])->assertSessionHasErrors('address');
    }

    public function testAddressMax200()
    {
        $this->itValidateField([ 'address' => $this->faker->paragraph(40) ])->assertSessionHasErrors('address');
    }


    public function testTelRequired()
    {
        $this->itValidateField([ 'tel' => '' ])->assertSessionHasErrors('tel');
    }

    public function testTelNumberDashCheck()
    {
        $this->itValidateField([ 'tel' => 'sdf sfdsf' ])->assertSessionHasErrors('tel');
    }

    public function testFaxRequired()
    {
        $this->itValidateField([ 'fax' => '' ])->assertSessionHasErrors('fax');
    }

    public function testFaxNumberDashCheck()
    {
        $this->itValidateField([ 'fax' => 'sdf sfdsf' ])->assertSessionHasErrors('fax');
    }


    public function testEmailRequired()
    {
        $this->itValidateField([ 'email' => '' ])->assertSessionHasErrors('email');
    }

    public function testEmailValid()
    {
        $this->itValidateField([ 'email' => 'df sfds' ])->assertSessionHasErrors('email');
    }

    public function testEmailUnique()
    {
        $contractor = factory(ContractInformation::class)->create();
        $this->itValidateField([ 'email' => $contractor->email ])->assertSessionHasErrors('email');
    }

    public function testLoginRequired()
    {
        $this->itValidateField([ 'login' => '' ])->assertSessionHasErrors('login');
    }

    public function testLoginUnique()
    {
        $hospital = factory(Hospital::class)->create();
        $this->itValidateField([ 'login' => $hospital->login_id ])->assertSessionHasErrors('login');
    }

    public function testPasswordRequired()
    {
        $this->itValidateField([ 'password' => '' ])->assertSessionHasErrors('password');
    }

    public function testStrongPasswordCheck()
    {
        $password_invalid_values = ['dfd', 'add233', 'addd@fff'];

        foreach ($password_invalid_values as $password_invalid_value) {
            $this->itValidateField([ 'password' => $password_invalid_value ])->assertSessionHasErrors('password');
        }
    }

    public function testOldKaradaDogIdRequired()
    {
        $this->itValidateField([ 'old_karada_dog_id' => '' ])->assertSessionHasErrors('old_karada_dog_id');
    }

    public function testOldKaradaDogIdStartLetter()
    {
        $this->itValidateField([ 'old_karada_dog_id' => 'B' ])->assertSessionHasErrors('old_karada_dog_id');
    }

    public function testKaradaDogIdRequired()
    {
        $this->itValidateField([ 'karada_dog_id' => '' ])->assertSessionHasErrors('karada_dog_id');
    }

    public function testKaradaDogIdValid()
    {
        $this->itValidateField([ 'karada_dog_id' => '232223' ])->assertSessionHasErrors('karada_dog_id');
    }

    public function testCanCreateContractFormData()
    {
        $response = $this->itValidateField($this->validFields());
        $this->assertEquals(302, $response->getStatusCode());
    }


    protected function itValidateField($attributes)
    {
        $this->withExceptionHandling();

        return $this->post($this->slug('/store'), $this->validFields($attributes));
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
        return array_merge([
            'medical_institution_name' => $this->faker->name,
            'contractor_name_kana'     => $this->faker->name,
            'contractor_name'          => $this->faker->name,
            'application_date'         => $this->faker->date('Y-m-d'),
            'billing_start_date'       => $this->faker->date('Y-m-d'),
            'cancellation_date'        => date('Y-m-d', now()->getTimestamp() + 10000000),
            'representative_name_kana' => $this->faker->name,
            'representative_name'      => $this->faker->name,
            'postcode'                 => '2939-3',
            'address'                  => $this->faker->address,
            'tel'                      => '959-9425301660', // can't use faker as faker generate phone number has other characters
            'fax'                      => '959-9425301660',
            'email'                    => $this->faker->unique()->email,
            'login'                    => $this->faker->userName,
            'password'                 => $this->faker->password(12),
            'old_karada_dog_id'        => 'K220',
            'karada_dog_id'            => 'Abd1330',
            '_token'                   => csrf_token(),
        ], $overwrites);
    }
}
