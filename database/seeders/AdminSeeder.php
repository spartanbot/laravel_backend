<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Hash;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('users')->insert([
            'full_name' => 'Admin',
            'user_name' => 'admin',
            'location' => 'us',
            'preferred_language' => 'en',
            'i_am_a' => 'admin',
            'affiliation' => 'Na',
            'subject' =>'Na',
            'age_group' => 'Na',
            'verified' => '1',
            'role' =>'0',
            'user_status' => '1',
            'user_email' => 'admin@gmail.com',
            'password' => Hash::make('daatt_admin2022'),
        ]);
    
    }
}
