<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Module;


class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Module::insert([
            [
                'title' => 'Users',
                'group_module' => 'user',
                'slug' => 'users-index',
                'ordering' => 1,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Users',
                'group_module' => 'user',
                'slug' => 'users-create',
                'ordering' => 2,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Admin Users',
                'group_module' => 'admin-user',
                'slug' => 'admin_users-index',
                'ordering' => 3,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Admin Users',
                'group_module' => 'admin-user',
                'slug' => 'admin_users-create',
                'ordering' => 4,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'CMS Pages',
                'group_module' => 'pages',
                'slug' => 'pages-index',
                'ordering' => 5,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'CMS Pages',
                'group_module' => 'pages',
                'slug' => 'pages-create',
                'ordering' => 6,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Email Templates',
                'group_module' => 'email-templates',
                'slug' => 'email_templates-index',
                'ordering' => 7,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Email Templates',
                'group_module' => 'email-templates',
                'slug' => 'email_templates-create',
                'ordering' => 8,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Setting Manager',
                'group_module' => 'settings',
                'slug' => 'settings-index',
                'ordering' => 9,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Setting Manager',
                'group_module' => 'settings',
                'slug' => 'settings-create',
                'ordering' => 10,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Blog',
                'group_module' => 'blog',
                'slug' => 'blog-index',
                'ordering' => 9,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Blog',
                'group_module' => 'blog',
                'slug' => 'blog-create',
                'ordering' => 10,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
