<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('tims', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255);
            $table->unsignedBigInteger('ptid');
            $table->boolean('setisinamalock')->default(false);
            $table->boolean('setisifotonota')->default(false);
            $table->boolean('setisifotonotarekap')->default(false);
            $table->boolean('setisinamalockval')->default(false);
            $table->boolean('setisifotonotaval')->default(false);
            $table->boolean('setisifotonotarekapval')->default(false);
            $table->unsignedBigInteger('userid');
            $table->timestamps();
            $table->foreign('ptid')->references('id')->on('pts');
            $table->foreign('userid')->references('id')->on('users');
            $table->unique(['nama', 'ptid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('tims');
    }
};
