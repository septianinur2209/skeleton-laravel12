<?php

namespace Database\Seeders;

use App\Models\Settings\SRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SRole::create([
            'role'  => "superadmin",
            'name'  => "Superadmin",
        ]);
    }
}
