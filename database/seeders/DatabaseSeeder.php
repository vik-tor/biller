<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Company;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $company = Company::create([
            'name' => "Default Company",
            'tax_pin' => "PA9898U98098",
            'address' => "P.O BOX 2456",
            'postal_code' => "00200",
            'town' => "Nairobi",
            'country' => "Kenya",
            'email' => "default.company@test.com",
            'phone' => "0798765432",
        ]);

        Client::create([
            'company_id' => $company->id,
            'name' => "Test Customer",
            'email' => "customer@test.com",
            'phone' => "0798765432",
        ]);
    }
}
