<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::create([
            'name' => 'Yape',
            'account_number' => '999 999 999',
            'qr_code_path' => 'https://res.cloudinary.com/wasi-pet/image/upload/v1678886400/yape_qr_code.png',
            'instructions' => 'Titular: Adra Uni',
        ]);

        PaymentMethod::create([
            'name' => 'Plin',
            'account_number' => '999 999 998',
            'qr_code_path' => 'https://res.cloudinary.com/wasi-pet/image/upload/v1678886400/plin_qr_code.png',
            'instructions' => 'Titular: Adra Uni',
        ]);

        PaymentMethod::create([
            'name' => 'BCP',
            'account_number' => '191-12345678-0-12',
            'cci' => '002191001234567801250',
            'instructions' => 'Titular: Adra Uni',
        ]);
    }
}
