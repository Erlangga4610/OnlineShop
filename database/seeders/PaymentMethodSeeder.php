<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        PaymentMethod::firstOrCreate(
            ['id' => 1],
            ['method_name' => 'Bank Transfer']
        );

        PaymentMethod::firstOrCreate(
            ['id' => 2],
            ['method_name' => 'E-Wallet']
        );

        PaymentMethod::firstOrCreate(
            ['id' => 3],
            ['method_name' => 'Credit Card']
        );
    }
}
