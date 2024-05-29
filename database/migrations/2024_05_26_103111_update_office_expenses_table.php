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
        Schema::table('office_expenses', function (Blueprint $table) {
            $table->unsignedBigInteger('expense_master_id');
            $table->foreign('expense_master_id')->references('id')->on('expense_masters');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('office_expenses', function (Blueprint $table) {
            //
        });
    }
};
