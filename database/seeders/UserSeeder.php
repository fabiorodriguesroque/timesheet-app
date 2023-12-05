<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(App::environment('local')) {
            User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@local.com',
            ]);
        }
    }
}
