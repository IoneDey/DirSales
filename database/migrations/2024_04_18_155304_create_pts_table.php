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
        Schema::create('pts', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255)->unique();
            $table->string('alamat', 255);
            $table->string('npwp', 16);
            $table->enum('pkp', ['Iya', 'Tidak']);
            $table->unsignedBigInteger('userid');
            $table->timestamps();
            $table->foreign('userid')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pts');
    }
};
