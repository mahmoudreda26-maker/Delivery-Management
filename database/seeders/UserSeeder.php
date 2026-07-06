<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
 public function run(): void
    {
        User::insert([
            [
                'name' => 'Ahmed Ali',
                'email' => 'ahmed@example.com',
                'password' => Hash::make('12345678'),
                'phone' => '01011111111',
                'role' => 'manager',
                'is_active' => true,
            ],
            [
                'name' => 'Mohamed Hassan',
                'email' => 'mohamed@example.com',
                'password' => Hash::make('12345678'),
                'phone' => '01022222222',
                'role' => 'driver',
                'is_active' => true,
            ],
            [
                'name' => 'Ali Ahmed',
                'email' => 'ali@example.com',
                'password' => Hash::make('12345678'),
                'phone' => '01033333333',
                'role' => 'driver',
                'is_active' => true,
            ],
            [
                'name' => 'Omar Khaled',
                'email' => 'omar@example.com',
                'password' => Hash::make('12345678'),
                'phone' => '01044444444',
                'role' => 'driver',
                'is_active' => false,
            ],
            [
                'name' => 'Sara Mostafa',
                'email' => 'sara@example.com',
                'password' => Hash::make('12345678'),
                'phone' => '01055555555',
                'role' => 'manager',
                'is_active' => true,
            ],
        ]);
    }
    }
    
