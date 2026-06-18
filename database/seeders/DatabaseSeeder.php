<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        $adminRole = Role::query()->where('name', 'admin')->first();
        $tutorRole = Role::query()->where('name', 'tutor')->first();

        User::query()->create([
            'role_id' => $adminRole->id,
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        User::query()->create([
            'role_id' => $tutorRole->id,
            'name' => 'Tutor User',
            'email' => 'tutor@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
