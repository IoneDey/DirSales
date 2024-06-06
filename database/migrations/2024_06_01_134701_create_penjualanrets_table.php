<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('penjualanrets', function (Blueprint $table) {
            $table->id();
            $table->date('tglretur');
            $table->string('noretur', 12);
            $table->unsignedBigInteger('timsetupid');
            $table->string('nota');
            $table->unsignedBigInteger('timsetuppaketid');
            $table->unsignedBigInteger('barangid');
            $table->integer('qty');
            $table->double('harga', 15, 8);
            $table->unsignedBigInteger('userid');
            $table->timestamps();
            $table->foreign('timsetuppaketid')->references('id')->on('timsetuppakets');
            $table->foreign('barangid')->references('id')->on('barangs');
            $table->foreign('userid')->references('id')->on('users');
            $table->unique(['noretur', 'timsetupid', 'nota']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('penjualanrets');
    }
};
