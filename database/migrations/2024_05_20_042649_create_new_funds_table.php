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
        Schema::create('new_funds', function (Blueprint $table) {
            $table->id();
            $table->string('Description');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('fund_categories')->onDelete('cascade');
            $table->decimal('amount',10,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_funds');
    }
};
