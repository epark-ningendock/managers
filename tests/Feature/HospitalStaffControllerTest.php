<?php

namespace Tests\Feature;

use App\Hospital;
use App\HospitalStaff;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class HospitalStaffControllerTest extends TestCase
{
	use DatabaseMigrations, RefreshDatabase;

	public function testItCanListPage()
	{
		$hospital = factory(Hospital::class)->create();
		factory(HospitalStaff::class, 50)->create(['hospital_id' => $hospital->id]);
		$HospitalStaff = HospitalStaff::paginate(20);

		$this->assertEquals(20, $HospitalStaff->count());
	}


	public function testItHasCreatePage()
	{
		$response = $this->get('/hospital-staff/create');

		$response->assertStatus(200);
	}

	public function testItHasEditPage()
	{
		$hospital_staff = factory(HospitalStaff::class)->create();

		$response = $this->get('/hospital-staff/'. $hospital_staff->id .'/edit');

		$response->assertStatus(200);
	}

	// ログイン機能ができた時に記載する
	// function testItHasEditPasswordPage()
	// {

	// 	$response = $this->get('/hospital-staff/edit-password/');

	// 	$response->assertStatus(200);

	// }

	public function testItHasShowPasswordResetsPage()
	{
		$response = $this->get('/hospital-staff/show-password-resets-mail/');

		$response->assertStatus(200);
	}

	public function testItHasShowResetPasswordPage()
	{
		$hospital = factory(Hospital::class)->create();
		$hospital_staff = factory(HospitalStaff::class)->create(['hospital_id' => $hospital->id]);

		$reset_token = str_random(32);
		$hospital_staff->reset_token_digest = bcrypt($reset_token);
		$hospital_staff->reset_sent_at = Carbon::now();
		$hospital_staff->save();

		$response = $this->get('hospital-staff/show-reset-password/'. $reset_token .'/'. $hospital_staff->email);

		$response->assertStatus(200);
	}
}
