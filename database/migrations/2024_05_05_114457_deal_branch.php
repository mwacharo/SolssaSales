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
        //
        Schema::create('deal_branch', function (Blueprint $table) {
            $table->unsignedBigInteger('deal_id');
            $table->unsignedBigInteger('branch_id');

            // Define foreign key constraints
            $table->foreign('deal_id')->references('id')->on('deals')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
