<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class AdminRoleModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'module_id' => 1,
                'admin_role_id' => 1,
            ],
            [
                'module_id' => 2,
                'admin_role_id' => 1,
            ],
            [
                'module_id' => 3,
                'admin_role_id' => 1,
            ],
            [
                'module_id' => 4,
                'admin_role_id' => 1,
            ],
            [
                'module_id' => 5,
                'admin_role_id' => 1,
            ],
            [
                'module_id' => 6,
                'admin_role_id' => 1,
            ],
            [
                'module_id' => 7,
                'admin_role_id' => 1,
            ],
            [
                'module_id' => 8,
                'admin_role_id' => 1,
            ],
            [
                'module_id' => 9,
                'admin_role_id' => 1,
            ],
            [
                'module_id' => 10,
                'admin_role_id' => 1,
            ],
            [
                'module_id' => 11,
                'admin_role_id' => 1,
            ],
            [
                'module_id' => 12,
                'admin_role_id' => 1,
            ],
        ];
        DB::table('admin_role_module')->insert($data);
    }
}
