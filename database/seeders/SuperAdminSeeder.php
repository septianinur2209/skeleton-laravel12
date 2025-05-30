<?php

namespace Database\Seeders;

use App\Models\Settings\SRole;
use App\Models\Settings\SUserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            "name"      => "Super Admin",
            "email"     => "superadmin@example.com",
            "password"  => bcrypt("password"),
        ]);

        SUserRole::create([
            "user_id"   => $user->id,
            "role_id"   => SRole::where('role', env("SUPERADMIN_ROLE_NAME", "superadmin"))->first()->id
        ]);
    }
}
