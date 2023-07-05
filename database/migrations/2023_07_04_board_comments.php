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
        Schema::create('board_comments', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->comment('게시판 내용');
            $table->increments('idx')->comment('pk');
            $table->integer('board_contents_idx')->comment('board_contents.idx')->nullable(false);
            $table->string('comments', 255)->comment('내용')->nullable(false);
            $table->string('is_delete')->default('N')->comment('삭제여부');
            $table->integer('user_id')->nullable(false)->comment('user.id');
            $table->string('ip', 100)->nullable(false)->comment('작성자 IP');
            $table->dateTime('created_dt')->useCurrent()->comment('생성일시');
            $table->datetime('updated_dt')->nullable(true)->default(null)->comment('수정일시');
            $table->datetime('deleted_dt')->nullable(true)->default(null)->comment('삭제일시');
            $table->index(['comments']);
            $table->index(['user_id']);
            $table->index(['board_contents_idx']);
            $table->index(['created_dt']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('board_comments');
    }
};
