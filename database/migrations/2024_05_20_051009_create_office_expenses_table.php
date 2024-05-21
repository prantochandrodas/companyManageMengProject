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
        Schema::create('office_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expense_category');
            $table->foreign('expense_category')->references('id')->on('expences')->onDelete('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('expense_head_category');
            $table->foreign('expense_head_category')->references('id')->on('expense_heads')->onDelete('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('fund_category');
            $table->foreign('fund_category')->references('id')->on('fund_categories')->onDelete('cascade')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->decimal('amount',10,2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_expenses');
    }
};
