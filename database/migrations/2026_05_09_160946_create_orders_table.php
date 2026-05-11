<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // 購入者
            $table->unsignedInteger('total_price');
            // 注文状態: 0=支払い待ち 1=支払い済み 2=発送済み 3=受取完了 4=キャンセル
            $table->unsignedTinyInteger('status')->default(0);
            $table->string('shipping_name');
            $table->string('shipping_postal_code', 8);
            $table->string('shipping_prefecture');
            $table->string('shipping_address');
            $table->string('shipping_building')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
