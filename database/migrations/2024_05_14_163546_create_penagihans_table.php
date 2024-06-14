<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('penagihans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('timsetupid');
            $table->string('nota');
            $table->date('tglpenagihan');
            $table->string('namapenagih', 150);
            $table->double('jumlah', 15, 8);
            $table->string('fotokwitansi')->nullable();
            $table->unsignedBigInteger('userid');
            $table->timestamps();
            $table->foreign('timsetupid')->references('id')->on('timsetups');
            $table->foreign('userid')->references('id')->on('users');
            $table->unique(['nota', 'tglpenagihan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('penagihans');
    }
};
