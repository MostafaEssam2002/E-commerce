<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // إضافة Foreign Key
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade'); // لو عايز يتمسح مع اليوزر
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // لازم تكتب اسم الـ constraint لو مسميه
            $table->dropForeign(['user_id']);
        });
    }
};
