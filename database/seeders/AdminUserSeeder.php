<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'admin@gmail.com'
            ],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin12')
            ]
        );

        User::updateOrCreate(
            [
                'email' => 'jasaraharja@gmail.com'
            ],
            [
                'name' => 'Jasa Raharja',
                'password' => Hash::make('jasaraharja123')
            ]
        );
    }
}