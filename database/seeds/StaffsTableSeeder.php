<?php

use Illuminate\Database\Seeder;
use \App\StaffAuth;

class StaffsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Staff::class, 50)->make()->each(function ($staff, $index) {
            $staff->login_id = "epark-dev-$index";
            $staff->save();
            factory(StaffAuth::class)->create(["staff_id" => $staff->id]);
        });

        // 動作確認用
        DB::table('staffs')->insert([
          'login_id' => 'eparkdock01',
          'name' => 'eparkdock01',
          'email' => 'epark01@example.com',
          'password' => Hash::make('password01'),
          'authority' => '1',
        ]);
    }
}
