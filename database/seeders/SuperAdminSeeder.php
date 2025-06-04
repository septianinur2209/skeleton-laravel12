<?php

namespace Database\Seeders;

use App\Models\Settings\SRole;
use App\Models\Settings\SUserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            "name"      => env("DEFAULT_NAME", "Superadmin"),
            "email"     => env("DEFAULT_EMAIL","superadmin@example.com"),
            "password"  => bcrypt(env("DEFAULT_PASSWORD","password")),
        ]);

        SUserRole::create([
            "user_id"   => $user->id,
            "role_id"   => SRole::where('role', env("SUPERADMIN_ROLE_NAME", "superadmin"))->first()->id
        ]);
    }
}
