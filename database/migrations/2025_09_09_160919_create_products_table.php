<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**


     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable(false);
            $table->text("description")->nullable();
            $table->decimal("price",8,2)->nullable(false);
            $table->decimal("shipping",8,2)->nullable(true)->default(0); ;
            $table->string("image_path")->nullable();
            $table->integer("quantity")->nullable()->default(0);
            $table->unsignedBigInteger('category_id');
            $table->foreign("category_id")->references('id')->on("categories");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
