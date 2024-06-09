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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('income_category_id');
            $table->foreign('income_category_id')->references('id')->on('income_categories');
            $table->unsignedBigInteger('income_head_id');
            $table->foreign('income_head_id')->references('id')->on('income_heads');
            $table->unsignedBigInteger('fund_category_id');
            $table->foreign('fund_category_id')->references('id')->on('fund_categories');
            $table->decimal('amount',15,2);
            $table->string('email');
            $table->string('phone_number', 15);
            $table->string('company_name');
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
