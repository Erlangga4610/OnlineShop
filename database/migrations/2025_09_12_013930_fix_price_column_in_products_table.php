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
        // Rename kolom salah jadi kolom benar
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 22, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke kolom lama (tidak direkomendasikan, tapi untuk rollback)
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price, 22, 2')->nullable()->change();
        });
    }
};
