<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'name' => "Default Company",
            'tax_pin' => "PA9898U98098",
            'address' => "P.O BOX 2456",
            'postal_code' => "00200",
            'town' => "Nairobi",
            'country' => "Kenya",
            'email' => "default.company@test.com",
            'phone' => "0798765432",
        ]);
    }
}
