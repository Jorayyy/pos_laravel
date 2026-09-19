<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('barcode')->nullable()->unique();
            $table->string('name');
            $table->foreignId('product_category_id')->nullable()->constrained()->nullOnDelete();
            $table->text('description')->nullable();
            $table->string('brand')->nullable();
            $table->string('unit')->default('pcs');
            $table->decimal('cost_price', 12, 2)->default(0);
            $table->decimal('selling_price', 12, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->integer('reorder_level')->default(5);
            $table->date('expiration_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('service_category_id')->nullable()->constrained()->nullOnDelete();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->integer('duration_minutes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
        Schema::dropIfExists('service_categories');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_categories');
    }
};
