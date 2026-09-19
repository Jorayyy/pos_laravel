<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('owners', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('full_name');
        });
        Schema::table('pets', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('name');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('owners', function (Blueprint $table) {
            $table->dropColumn('photo');
        });
        Schema::table('pets', function (Blueprint $table) {
            $table->dropColumn('photo');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('photo');
        });
    }
};
