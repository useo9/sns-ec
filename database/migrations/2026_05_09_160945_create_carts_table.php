<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();    // 購入者
            $table->foreignId('product_id')->constrained()->cascadeOnDelete(); // 古着は1点物なので数量なし
            $table->timestamps();

            $table->unique(['user_id', 'product_id']); // 同一商品の重複追加防止
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
