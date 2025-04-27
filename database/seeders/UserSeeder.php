<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'TUPAdmin',
            'email' => 'Admin@tup.com',
            'password' => Hash::make('TUPAdmin'),
            'phone' => null,
            'location' => null,
            'about_me' => null,
            'remember_token' => null,
            'role' => 'admin',
            'department' => 'Administration',
            'branch' => 'Inventory Office',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
