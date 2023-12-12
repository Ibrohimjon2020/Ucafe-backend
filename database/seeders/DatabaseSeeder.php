<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        Role::create([
            'title' => [
                'uz' => 'Administrator',
                'ru' => 'Администратор',
                'en' => 'Administrator'
            ]
        ]);
        Role::create([
            'title' => [
                'uz' => 'Waiter',
                'ru' => 'Официант',
                'en' => 'Waiter'
            ]
        ]);

        Role::create([
            'title' => [
                'uz' => 'Kuryer',
                'ru' => 'Курьер',
                'en' => 'Carrier'
            ]
        ]);

        Role::create([
            'title' => [
                'uz' => 'Barista',
                'ru' => 'Бариста',
                'en' => 'Barista'
            ]
        ]);

        Role::create([
            'title' => [
                'uz' => 'Yangi',
                'ru' => 'Новый',
                'en' => 'New'
            ]
        ]);

        $user = \App\Models\User::factory()->create([
            'name' => 'Super user',
            'email' => 'admin@admin.com',
            'phone_number' => '901234569',
            'login' => 'megaadmin'
        ]);

        $user->giveRole('Administrator');
    }
}
