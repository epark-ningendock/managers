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
            factory(HospitalStaff::class, 50)->create([
                'hospital_id' => $hospital->id,
            ]);
        }

        // 動作確認用
        DB::table('hospital_staffs')->insert([
        'login_id' => 'eparkdock02',
        'name' => 'eparkdock02',
        'email' => 'epark02@example.com',
        'password' => Hash::make('password02'),
        'hospital_id' => '1',
      ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        HospitalStaff::reguard();
    }
}
