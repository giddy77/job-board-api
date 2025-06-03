<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Alice Wanjiku', 'email' => 'alice@example.com'],
            ['name' => 'Brian Otieno', 'email' => 'brian@example.com'],
            ['name' => 'Carol Mwangi', 'email' => 'carol@example.com'],
            ['name' => 'Daniel Kiptoo', 'email' => 'daniel@example.com'],
            ['name' => 'Emily Njoki', 'email' => 'emily@example.com'],
            ['name' => 'Frank Ochieng', 'email' => 'frank@example.com'],
            ['name' => 'Grace Ndungu', 'email' => 'grace@example.com'],
            ['name' => 'Henry Mutiso', 'email' => 'henry@example.com'],
            ['name' => 'Irene Muthoni', 'email' => 'irene@example.com'],
            ['name' => 'James Kilonzo', 'email' => 'james@example.com'],
        ];

        foreach ($users as $data) {
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // default password
                'remember_token' => Str::random(10),
            ]);
        }
    }
}
