<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('page_url');
            $table->string('page_title')->default("home")->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->string('device_type')->nullable(); // desktop, mobile, tablet
            $table->string('referrer')->nullable();
            $table->timestamp('viewed_at');
            $table->integer('time_spent')->default(0); // بالثواني
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index('page_url');
            $table->index('viewed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
