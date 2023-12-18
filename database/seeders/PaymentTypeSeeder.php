<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use Illuminate\Database\Seeder;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        PaymentType::create([
            'title' => [
                'uz' => 'Naqt',
                'ru' => 'Наличные',
                'en' => 'Cash'
            ]
        ]);
        PaymentType::create([
            'title' => [
                'uz' => 'o\'tkazma',
                'ru' => 'переводом',
                'en' => 'transfer'
            ]
        ]);
    }
}
