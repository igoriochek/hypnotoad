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
            $table->string('code')->unique();
            $table->json('badge');       // multilingual: {lt, en, ru}
            $table->json('title');       // multilingual: {lt, en, ru}
            $table->json('description'); // multilingual: {lt, en, ru}
            $table->json('meta');        // multilingual: {lt, en, ru}
            $table->json('price_display'); // multilingual: {lt, en, ru} e.g. "132 €" or "Kaina derinama"
            $table->unsignedInteger('price_cents')->nullable(); // null = price on request
            $table->boolean('requires_shipping')->default(false);
            $table->boolean('payable')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
