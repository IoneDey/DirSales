<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 10)->unique();
            $table->string('nama', 150);
            $table->string('notelp', 20);
            $table->boolean('flagdriver')->default(false);
            $table->boolean('flagkolektor')->default(false);
            $table->boolean('void')->default(false);
            $table->unsignedBigInteger('userid');
            $table->timestamps();
            $table->foreign('userid')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('karyawans');
    }
};
