<?php

use Illuminate\Database\Seeder;

class StaffAuthsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('staff_auths')->insert([
            'is_hospital' => 1,
            'is_staff' => 1,
            'is_item_category' => 1,
            'is_invoice' => 1,
            'is_pre_account' => 1,
            'staff_id' => 1
        ]);
    }
}
