<?php

namespace Database\Seeders;

use App\Models\Master\MMenu;
use App\Models\Settings\SMenuAccess;
use App\Models\Settings\SRole;
use Illuminate\Database\Seeder;

class SuperAdminPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        $menu_list = MMenu::all();
        $role_id = SRole::where('role', env("SUPERADMIN_ROLE_NAME", "superadmin"))->first()->id;

        foreach ($menu_list as $key => $value) {

            $data[] = [
                'menu_id' => $value->id,
                'role_id' => $role_id,
                'show' => 1,
                'create' => 1,
                'edit' => 1,
                'delete' => 1
            ];
        }

        SMenuAccess::insert($data);
    }
}
