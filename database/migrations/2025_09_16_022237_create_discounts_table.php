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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id(); // id default (bigint auto increment)
            $table->string('code')->unique(); // kode voucher/promo
            $table->string('description')->nullable();
            $table->enum('type', ['voucher', 'bundling'])->default('voucher');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('discount_value', 10, 2);
            $table->decimal('min_purchase', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
