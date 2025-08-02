<?php

namespace Database\Seeders;

use App\Enums\UserTypeEnum;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $hashedPassword = Hash::make('12345678');
        $adminType = UserTypeEnum::Admin->value;

        $sql = "INSERT INTO users (name, username, email, password, type, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, NOW(), NOW())
        ON DUPLICATE KEY UPDATE
            name = VALUES(name),
            username = VALUES(username),
            password = VALUES(password),
            type = VALUES(type),
            updated_at = NOW()";

        DB::statement($sql, [
            'Admin',
            'admin',
            'admin@admin.com',
            $hashedPassword,
            $adminType
        ]);
    }
}
