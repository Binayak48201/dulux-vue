<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->insert([
            'name' => 'superadmin',
            'email' => 'superadmin@email.com',
            'password' => Hash::make('password'),
            'company_id' => 0,
        ]);

        User::create([
            'name' => 'national_sales_manager',
            'email' => 'nationalsalesmanager@email.com',
            'password' => Hash::make('password'),
            'company_id' => 0,
        ]);

        User::create([
            'name' => 'state_sales_manager',
            'email' => 'statesalesmanager@email.com',
            'password' => Hash::make('password'),
            'company_id' => 0,
        ]);

    }
}
