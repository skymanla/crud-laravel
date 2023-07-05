<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BoardContentsSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        DB::table('board_contents')->insert([
            [
                'board_idx' => 1,
                'contents_category_idx' => 1,
                'title' => Str::random(10),
                'contents' => Str::random(16),
                'user_id' => 1,
                'ip' => '127.0.0.1'
            ],
            [
                'board_idx' => 1,
                'contents_category_idx' => 1,
                'title' => Str::random(10),
                'contents' => Str::random(16),
                'user_id' => 2,
                'ip' => '127.0.0.1'
            ],
            [
                'board_idx' => 1,
                'contents_category_idx' => 2,
                'title' => Str::random(10),
                'contents' => Str::random(16),
                'user_id' => 3,
                'ip' => '127.0.0.1'
            ],
            [
                'board_idx' => 2,
                'contents_category_idx' => 1,
                'title' => Str::random(10),
                'contents' => Str::random(16),
                'user_id' => 4,
                'ip' => '127.0.0.1'
            ],
            [
                'board_idx' => 3,
                'contents_category_idx' => 1,
                'title' => Str::random(10),
                'contents' => Str::random(16),
                'user_id' => 5,
                'ip' => '127.0.0.1'
            ],
        ]);
    }
}
