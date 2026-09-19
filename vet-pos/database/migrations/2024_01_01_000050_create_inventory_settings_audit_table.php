<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->integer('quantity');
            $table->integer('previous_stock');
            $table->integer('new_stock');
            $table->string('reason')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sale_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            $table->index('product_id');
            $table->index('type');
            $table->index('created_at');
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event');
            $table->string('auditable_type')->nullable();
            $table->unsignedBigInteger('auditable_id')->nullable();
            $table->text('old_values')->nullable();
            $table->text('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['auditable_type', 'auditable_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('inventory_movements');
    }
};
