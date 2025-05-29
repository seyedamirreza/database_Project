<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $cities = ['Tehran', 'Shiraz', 'Tabriz', 'Isfahan', 'Mashhad'];
        $roles = ['user', 'admin'];
        $data = [];

        for ($i = 1; $i <= 10; $i++) {
            $data[] = [
                'firstName'     => "TestFirst$i",
                'lastName'      => "TestLast$i",
                'password'      => Hash::make("password$i"),
                'phoneNumber'   => "0912000000$i",
                'role'          => $roles[array_rand($roles)],
                'registerDate'  => Carbon::now()->subDays(rand(0, 30))->toDateString(),
                'city'          => $cities[array_rand($cities)],
                'email'         => rand(0, 1) ? "user$i@example.com" : null,
                'accountState'  => rand(0, 1),
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        DB::table('users')->insert($data);
    }
}
