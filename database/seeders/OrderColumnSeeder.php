<?php

namespace Database\Seeders;

use App\Models\OrderColumn;
use Illuminate\Database\Seeder;

class OrderColumnSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        OrderColumn::create([
            'title' => [
                'uz' => 'Yangi',
                'ru' => 'Новый',
                'en' => 'New'
            ]
        ]);
        OrderColumn::create([
            'title' => [
                'uz' => 'Jarayonda',
                'ru' => 'В процессе',
                'en' => 'In progress'
            ]
        ]);
        OrderColumn::create([
            'title' => [
                'uz' => 'Tayyor',
                'ru' => 'Готовые',
                'en' => 'Ready'
            ]
        ]);
    }
}
