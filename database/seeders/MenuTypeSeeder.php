<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuType;

class MenuTypeSeeder extends Seeder
{
    public function run()
    {
        $menuTypes = [
            [
                'title' => ['en' => 'Deserts', 'ru' => 'Десерты', 'uz' => 'Shirinliklar'],
                'order_number' => '6',
                'logo' => '<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">rn<path d="M18.9166 10.5H20.3063C22.1578 10.5 22.7081 10.7655 22.6635 12.0838C22.5898 14.2674 21.6051 16.8047 17.666 17.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>rn<path d="M6.61228 20.6145C3.23787 18.02 2.7407 14.3401 2.66745 10.5001C2.6358 8.8413 3.11728 8.5 5.3252 8.5H16.0068C18.2147 8.5 18.6962 8.8413 18.6646 10.5001C18.5913 14.3401 18.0942 18.02 14.7198 20.6145C13.7594 21.3528 12.9492 21.5 11.5854 21.5H9.74666C8.38287 21.5 7.57259 21.3528 6.61228 20.6145Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>rn<path d="M11.975 2.5C11.4282 2.83861 10.6673 4 10.6673 5.5M8.20572 4C8.20572 4 7.66602 4.5 7.66602 5.5M14.6673 4C14.3939 4.1693 14.166 5 14.166 5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>rn</svg>',
            ],
            [
                'title' => ['en' => 'PE', 'ru' => 'ПП', 'uz' => 'TO'],
                'order_number' => '5',
                'logo' => '<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">rn<path d="M18.9166 10.5H20.3063C22.1578 10.5 22.7081 10.7655 22.6635 12.0838C22.5898 14.2674 21.6051 16.8047 17.666 17.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>rn<path d="M6.61228 20.6145C3.23787 18.02 2.7407 14.3401 2.66745 10.5001C2.6358 8.8413 3.11728 8.5 5.3252 8.5H16.0068C18.2147 8.5 18.6962 8.8413 18.6646 10.5001C18.5913 14.3401 18.0942 18.02 14.7198 20.6145C13.7594 21.3528 12.9492 21.5 11.5854 21.5H9.74666C8.38287 21.5 7.57259 21.3528 6.61228 20.6145Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>rn<path d="M11.975 2.5C11.4282 2.83861 10.6673 4 10.6673 5.5M8.20572 4C8.20572 4 7.66602 4.5 7.66602 5.5M14.6673 4C14.3939 4.1693 14.166 5 14.166 5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>rn</svg>',
            ],
            [
                'title' => ['en' => 'Snacks', 'ru' => 'Перекусы', 'uz' => 'Qarsildoqlar'],
                'order_number' => '4',
                'logo' => '<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">rn<path d="M18.9166 10.5H20.3063C22.1578 10.5 22.7081 10.7655 22.6635 12.0838C22.5898 14.2674 21.6051 16.8047 17.666 17.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>rn<path d="M6.61228 20.6145C3.23787 18.02 2.7407 14.3401 2.66745 10.5001C2.6358 8.8413 3.11728 8.5 5.3252 8.5H16.0068C18.2147 8.5 18.6962 8.8413 18.6646 10.5001C18.5913 14.3401 18.0942 18.02 14.7198 20.6145C13.7594 21.3528 12.9492 21.5 11.5854 21.5H9.74666C8.38287 21.5 7.57259 21.3528 6.61228 20.6145Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>rn<path d="M11.975 2.5C11.4282 2.83861 10.6673 4 10.6673 5.5M8.20572 4C8.20572 4 7.66602 4.5 7.66602 5.5M14.6673 4C14.3939 4.1693 14.166 5 14.166 5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>rn</svg>',
            ],
            [
                'title' => ['en' => 'Bar', 'ru' => 'Бар', 'uz' => 'Bar'],
                'order_number' => '3',
                'logo' => '<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">rn<path d="M18.9166 10.5H20.3063C22.1578 10.5 22.7081 10.7655 22.6635 12.0838C22.5898 14.2674 21.6051 16.8047 17.666 17.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>rn<path d="M6.61228 20.6145C3.23787 18.02 2.7407 14.3401 2.66745 10.5001C2.6358 8.8413 3.11728 8.5 5.3252 8.5H16.0068C18.2147 8.5 18.6962 8.8413 18.6646 10.5001C18.5913 14.3401 18.0942 18.02 14.7198 20.6145C13.7594 21.3528 12.9492 21.5 11.5854 21.5H9.74666C8.38287 21.5 7.57259 21.3528 6.61228 20.6145Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>rn<path d="M11.975 2.5C11.4282 2.83861 10.6673 4 10.6673 5.5M8.20572 4C8.20572 4 7.66602 4.5 7.66602 5.5M14.6673 4C14.3939 4.1693 14.166 5 14.166 5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>rn</svg>',
            ],
            [
                'title' => ['en' => 'Lunch', 'ru' => 'Обеды', 'uz' => 'Obed'],
                'order_number' => '2',
                'logo' => '<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">rn<path d="M18.9166 10.5H20.3063C22.1578 10.5 22.7081 10.7655 22.6635 12.0838C22.5898 14.2674 21.6051 16.8047 17.666 17.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>rn<path d="M6.61228 20.6145C3.23787 18.02 2.7407 14.3401 2.66745 10.5001C2.6358 8.8413 3.11728 8.5 5.3252 8.5H16.0068C18.2147 8.5 18.6962 8.8413 18.6646 10.5001C18.5913 14.3401 18.0942 18.02 14.7198 20.6145C13.7594 21.3528 12.9492 21.5 11.5854 21.5H9.74666C8.38287 21.5 7.57259 21.3528 6.61228 20.6145Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>rn<path d="M11.975 2.5C11.4282 2.83861 10.6673 4 10.6673 5.5M8.20572 4C8.20572 4 7.66602 4.5 7.66602 5.5M14.6673 4C14.3939 4.1693 14.166 5 14.166 5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>rn</svg>',
            ],
            [
                'title' => ['en' => 'Breakfast', 'ru' => 'Завтраки', 'uz' => 'Nonushta'],
                'order_number' => '1',
                'logo' => 'svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">rn<path d="M18.9166 10.5H20.3063C22.1578 10.5 22.7081 10.7655 22.6635 12.0838C22.5898 14.2674 21.6051 16.8047 17.666 17.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>rn<path d="M6.61228 20.6145C3.23787 18.02 2.7407 14.3401 2.66745 10.5001C2.6358 8.8413 3.11728 8.5 5.3252 8.5H16.0068C18.2147 8.5 18.6962 8.8413 18.6646 10.5001C18.5913 14.3401 18.0942 18.02 14.7198 20.6145C13.7594 21.3528 12.9492 21.5 11.5854 21.5H9.74666C8.38287 21.5 7.57259 21.3528 6.61228 20.6145Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>rn<path d="M11.975 2.5C11.4282 2.83861 10.6673 4 10.6673 5.5M8.20572 4C8.20572 4 7.66602 4.5 7.66602 5.5M14.6673 4C14.3939 4.1693 14.166 5 14.166 5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>rn</svg>"',
            ],
        ];

        foreach ($menuTypes as $menuTypeData) {
            MenuType::create([
                'title' => $menuTypeData['title'],
                'logo' => $menuTypeData['logo'],
                'order_number' => $menuTypeData['order_number'],
            ]);
        }
    }
}
