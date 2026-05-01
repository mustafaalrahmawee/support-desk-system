<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach (['admin', 'support_agent', 'inbound_reviewer', 'contract_manager'] as $roleName) {
            Role::findOrCreate($roleName, 'web');
        }

        $admin = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'Admin',
            'username' => 'admin',
            'email' => 'test@example.com',
        ]);

        $admin->assignRole('admin');
    }
}
