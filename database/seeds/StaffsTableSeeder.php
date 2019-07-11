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
        // 動作確認用
        DB::table('staffs')->insert([
            'id' => 1,
            'login_id' => 'eparkdock01',
            'name' => 'eparkdock01',
            'email' => 'epark01@example.com',
            'password' => Hash::make('password01'),
            'authority' => '1',
            'department_id' => rand(1, 10)
        ]);
        
        factory(StaffAuth::class)->create([
            'is_hospital' => 3,
            'is_staff' => 3,
            'is_cource_classification' => 3,
            'is_invoice' => 3,
            'is_pre_account' => 7,
            'is_contract' => 7,
            "staff_id" => 1
        ]);

        factory(App\Staff::class, 50)->make()->each(function ($staff, $index) {
            $staff->login_id = "epark-dev-$index";
            $staff->save();
            factory(StaffAuth::class)->create(["staff_id" => $staff->id]);
        });
    }
}
