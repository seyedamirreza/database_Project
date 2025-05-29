<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompaniesTableSeeder extends Seeder
{
    public function run()
    {
        // پاک کردن داده‌های قبلی (اختیاری)
        DB::table('companies')->truncate();

        // درج داده‌های تستی
        DB::table('companies')->insert([
            ['name' => 'شرکت الف'],
            ['name' => 'شرکت ب'],
            ['name' => 'شرکت ج'],
            ['name' => 'شرکت د'],
            ['name' => 'شرکت ه'],
        ]);
    }
}
