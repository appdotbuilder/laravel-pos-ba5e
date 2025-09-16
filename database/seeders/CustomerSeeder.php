<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some VIP customers
        Customer::factory(5)->vip()->create();

        // Create regular customers
        Customer::factory(20)->create();

        // Create new customers
        Customer::factory(10)->newCustomer()->create();

        // Create inactive customers
        Customer::factory(5)->inactive()->create();
    }
}