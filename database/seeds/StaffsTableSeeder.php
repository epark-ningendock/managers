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

        // TODO　動作確認用 マージ前に削除
        DB::table('staffs')->insert([
          'login_id' => 'aaaaaaaa',
          'name' => 'user',
          'email' => 'user@example.com',
          'password' => Hash::make('11111111'),
          'authority' => '1',
        ]);
    }
}
