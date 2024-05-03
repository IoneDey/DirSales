<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('penjualandts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penjualanhdid');
            $table->unsignedBigInteger('timsetuppaketid');
            $table->integer('jumlah');
            $table->unsignedBigInteger('userid');
            $table->timestamps();
            $table->foreign('penjualanhdid')->references('id')->on('penjualanhds')->onDelete('cascade');;
            $table->foreign('timsetuppaketid')->references('id')->on('timsetuppakets');
            $table->foreign('userid')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('penjualandts');
    }
};
