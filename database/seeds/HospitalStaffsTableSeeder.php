<?php

use Illuminate\Database\Seeder;

class HospitalStaffsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    factory(App\HospitalStaff::class, 50)->create();

      // TODO　動作確認用 マージ前に削除
      DB::table('hospital_staffs')->insert([
        'login_id' => '12345',
        'name' => 'user',
        'email' => 'user@example.com',
        'password' => Hash::make('1111'),
      ]);

    }
}
