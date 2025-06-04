<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $count = User::count();
        $target = 100;

        if ($count < $target) {
            User::factory($target - $count)->create();
        }
    }
}
