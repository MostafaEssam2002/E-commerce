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
        Schema::create('copons', function (Blueprint $table) {
            $table->id();
            $table->string("copon");
            $table->decimal("value",8,2);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('copons');
    }
};
