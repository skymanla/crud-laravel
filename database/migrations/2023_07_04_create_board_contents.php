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
        Schema::create('board_contents', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->comment('게시판 내용');
            $table->increments('idx')->comment('pk');
            $table->integer('board_idx')->comment('board.idx')->nullable(false);
            $table->integer('contents_category_idx')->comment('contents_category.idx')->nullable(false);
            $table->string('title', 100)->comment('제목')->nullable(false);
            $table->string('contents', 255)->comment('내용')->nullable(false);
            $table->string('is_delete')->default('N')->comment('삭제여부');
            $table->string('writer', 20)->nullable(false)->comment('작성자');
            $table->string('pwd', 255)->nullable(false)->comment('게시글 비밀번호');
            $table->string('ip', 100)->nullable(false)->comment('작성자 IP');
            $table->string('has_comment', 1)->default('Y')->comment('댓글 작성 가능 여부');
            $table->integer('hit_count')->default(0)->comment('hit count');
            $table->dateTime('created_dt')->useCurrent()->comment('생성일시');
            $table->datetime('updated_dt')->nullable(true)->default(null)->comment('수정일시');
            $table->datetime('deleted_dt')->nullable(true)->default(null)->comment('삭제일시');
            $table->index(['title']);
            $table->index(['contents']);
            $table->index(['writer']);
            $table->index(['board_idx']);
            $table->index(['contents_category_idx']);
            $table->index(['created_dt']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('board_contents');
    }
};
