<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('board_read_history', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->comment('게시판 읽기 히스토리');
            $table->increments('idx')->comment('pk');
            $table->integer('user_id')->nullable(false)->comment('user.id');
            $table->string('ip', 100)->comment('ip')->nullable(false);
            $table->integer('board_contents_idx')->comment('board_contents.idx')->nullable(false);
            $table->dateTime('created_dt')->useCurrent()->comment('생성일시');
            $table->index(['ip']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('board_read_history');
    }
};
