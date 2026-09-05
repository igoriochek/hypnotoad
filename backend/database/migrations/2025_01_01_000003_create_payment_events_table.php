<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_events', function (Blueprint $table) {
            $table->id();
            $table->string('provider_event_id')->unique();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event_type')->nullable();
            $table->boolean('verified')->default(false);
            $table->text('raw_payload')->nullable();
            $table->timestamp('received_at')->useCurrent();

            $table->index('order_id');
            $table->index('verified');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_events');
    }
};
