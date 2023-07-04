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
        Schema::create('contents_category', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->comment('게시판 카테고리');
            $table->increments('idx')->comment('pk');
            $table->string('title', 100)->comment('카테고리명');
            $table->string('is_use', 1)->comment('사용여부(Y/N)')->default('Y');
            $table->dateTime('created_dt')->useCurrent()->comment('생성일시');
            $table->datetime('updated_dt')->nullable(true)->default(null)->comment('수정일시');
            $table->index(['title']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('contents_category');
    }
};
