<?php

use Illuminate\Database\Seeder;
use App\Hospital;
use App\HospitalStaff;

class HospitalStaffsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $hospitals = Hospital::all();
      HospitalStaff::unguard();
      DB::statement('SET FOREIGN_KEY_CHECKS=0;');

      foreach ($hospitals as $hospital) {
	      factory(HospitalStaff::class, 50)->create();
      }

      // TODO　動作確認用 マージ前に削除
      DB::table('hospital_staffs')->insert([
        'login_id' => '12345',
        'name' => 'user',
        'email' => 'user@example.com',
        'password' => Hash::make('1111'),
        'hospital_id' => '1',
      ]);

      DB::statement('SET FOREIGN_KEY_CHECKS=1;');
      HospitalStaff::reguard();
    }
}
