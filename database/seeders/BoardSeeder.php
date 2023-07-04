<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BoardSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        DB::table('board')->insert([
            [
                'title' => '자유게시판',
                'is_use' => 'Y',
            ],
            [
                'title' => '공지사항',
                'is_use' => 'Y',
            ],
            [
                'title' => '질문게시판',
                'is_use' => 'Y',
            ]
        ]);
    }
}
