<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            ['name' => 'Pesa Kit', 'email' => 'hr@pesakit.com', 'location' => 'Nairobi'],
            ['name' => 'JuaKali Hub', 'email' => 'jobs@juakali.co.ke', 'location' => 'Mombasa'],
            ['name' => 'Twende Africa', 'email' => 'careers@twende.africa', 'location' => 'Kisumu'],
            ['name' => 'Zuri Digital', 'email' => 'hello@zuridigital.com', 'location' => 'Nairobi'],
            ['name' => 'AgroTech Farm', 'email' => 'contact@agrotech.co.ke', 'location' => 'Eldoret'],
            ['name' => 'FinSave Ltd', 'email' => 'talent@finsave.io', 'location' => 'Nakuru'],
            ['name' => 'AfriNet', 'email' => 'admin@afrinetworks.com', 'location' => 'Thika'],
            ['name' => 'SmartGrid Energy', 'email' => 'info@smartgrid.africa', 'location' => 'Machakos'],
            ['name' => 'Kibo Systems', 'email' => 'recruit@kibo.tech', 'location' => 'Nyeri'],
            ['name' => 'UrbanCode Kenya', 'email' => 'jobs@urbancode.ke', 'location' => 'Nairobi'],
        ];

        foreach ($companies as $company) {
            Company::create([
                'name' => $company['name'],
                'email' => $company['email'],
                'password' => Hash::make('password'),
                'location' => $company['location'],
            ]);

        }
    }
}
