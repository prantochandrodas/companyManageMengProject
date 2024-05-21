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
        Schema::create('fund_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('openingAmount',15,2)->default(0.00);
            $table->decimal('addedFundAmount',15,2)->default(0.00);
            $table->decimal('expensedAmount',15,2)->default(0.00);
            $table->decimal('total',15,2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fund_categories');
    }
};
