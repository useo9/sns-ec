<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // 出品者
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('price');
            $table->string('brand')->nullable();
            $table->string('size')->nullable();   // XS S M L XL etc.
            $table->string('category')->nullable(); // トップス ボトムス アウター etc.
            // 商品の状態: 1=新品未使用 2=未使用に近い 3=目立った傷汚れなし 4=やや傷汚れあり 5=傷汚れあり 6=全体的に状態が悪い
            $table->unsignedTinyInteger('condition')->default(3);
            // 出品状態: 0=下書き 1=出品中 2=売り切れ 3=取引中
            $table->unsignedTinyInteger('status')->default(1);
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
