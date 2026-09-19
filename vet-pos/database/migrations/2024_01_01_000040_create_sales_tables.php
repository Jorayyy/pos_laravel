<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('owner_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('pet_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('discount', 14, 2)->default(0);
            $table->decimal('tax', 14, 2)->default(0);
            $table->decimal('total', 14, 2)->default(0);
            $table->string('payment_method')->default('cash');
            $table->decimal('amount_paid', 14, 2)->default(0);
            $table->decimal('change_amount', 14, 2)->default(0);
            $table->string('status')->default('completed');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('created_at');
            $table->index('status');
        });

        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type');
            $table->string('name');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total', 14, 2)->default(0);
            $table->timestamps();

            $table->index('sale_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sales');
    }
};
