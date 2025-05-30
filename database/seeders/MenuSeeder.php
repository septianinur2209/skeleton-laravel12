<?php

namespace Database\Seeders;

use App\Models\Master\MMenu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menu = [
            [
                'menu'          => 'setting.user',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],[
                'menu'          => 'setting.role',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],[
                'menu'          => 'master.menu',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],[
                'menu'          => 'setting.menu-access',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]
        ];

        MMenu::insert($menu);
    }
}
