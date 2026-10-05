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
        Schema::create('order_adjustments', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // ADJ-000001
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users');
            $table->string('status')->default('pending')->index(); // pending, approved, rejected
            $table->text('reason');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('rejected_reason')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'status']);
        });

        Schema::create('order_adjustment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_adjustment_id')->constrained('order_adjustments')->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $table->string('old_sku');
            $table->string('new_sku');
            $table->integer('old_quantity');
            $table->integer('new_quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_adjustment_items');
        Schema::dropIfExists('order_adjustments');
    }
};
