<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ptid');
            $table->string('nama', 150);
            $table->string('notelp', 20);
            $table->unsignedBigInteger('userid');
            $table->timestamps();
            $table->foreign('ptid')->references('id')->on('pts');
            $table->foreign('userid')->references('id')->on('users');
            $table->unique(['ptid', 'nama']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('sales');
    }
};
