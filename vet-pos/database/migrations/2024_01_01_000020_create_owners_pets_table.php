<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('owners', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('species');
            $table->string('breed')->nullable();
            $table->string('sex')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('color')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('microchip_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pets');
        Schema::dropIfExists('owners');
    }
};
