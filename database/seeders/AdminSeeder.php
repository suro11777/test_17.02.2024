<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!User::where('email', 'admin@test.am')->first()){
            DB::table('users')->insert([
                [
                    'id' => 1,
                    'name' => 'Admin',
                    'email' => 'admin@test.am',
                    'role' => \ConstUserRole::ADMIN,
                    'password' => \Illuminate\Support\Facades\Hash::make('admintest'),
                ]
            ]);
        }

    }
}
