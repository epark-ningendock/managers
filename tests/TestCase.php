<?php

namespace Tests;

use App\HospitalStaff;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;


    protected function hospitalStaffSignIn($user = null)
    {
    	$user = $user ?: factory(HospitalStaff::class)->create();
    	$this->actingAs($user);

    	return $user;
    }
}
