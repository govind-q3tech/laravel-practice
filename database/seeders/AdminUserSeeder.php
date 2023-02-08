<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\AdminUser;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdminUser::insert([
            [ 
                'first_name' => 'John',
                'last_name' => 'Due',
                'email' => 'john@gmail.com',
                'password' => Hash::make('password'),
                'email_verified_at' => date('Y-m-d H:i:s'), 
                'role_id' => 1, 
                'status' => 1, 
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ]); 
    }
}
