<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $adminEmail    = env('ADMIN_EMAIL', 'nguyendanhdung479@gmail.com');
        $adminPassword = env('ADMIN_PASSWORD', 'dung2005');

        User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name'              => '[SAMPLE] Admin',
                'password'          => Hash::make($adminPassword),
                'role'              => 'admin',
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );

        // Editor
        $editorEmail    = env('EDITOR_EMAIL', 'rungadventure@gmail.com');
        $editorPassword = env('EDITOR_PASSWORD', 'dung2005');

        User::firstOrCreate(
            ['email' => $editorEmail],
            [
                'name'              => '[SAMPLE] Editor',
                'password'          => Hash::make($editorPassword),
                'role'              => 'editor',
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✓ Đã seed users:');
        $this->command->line("   - Admin: {$adminEmail}");
        $this->command->line("   - Editor: {$editorEmail}");
    }
}