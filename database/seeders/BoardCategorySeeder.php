<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BoardCategorySeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        DB::table('contents_category')->insert([
            [
                'title' => '일반',
                'is_use' => 'Y',
            ],
            [
                'title' => '자유',
                'is_use' => 'Y',
            ],
            [
                'title' => '질문',
                'is_use' => 'Y',
            ],
            [
                'title' => '비틱',
                'is_use' => 'N',
            ]
        ]);
    }
}
