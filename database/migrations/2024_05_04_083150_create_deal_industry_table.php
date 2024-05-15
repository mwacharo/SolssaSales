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
        // Schema::create('deal_industry', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });
        Schema::create('deal_industry', function (Blueprint $table) {
            $table->unsignedBigInteger('deal_id');
            $table->unsignedBigInteger('industry_id');
            $table->primary(['deal_id', 'industry_id']);
            $table->foreign('deal_id')->references('id')->on('deals')->onDelete('cascade');
            $table->foreign('industry_id')->references('id')->on('industries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deal_industry');
    }
};
