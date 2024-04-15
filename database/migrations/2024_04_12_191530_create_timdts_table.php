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
        Schema::create('timdt', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nomerid');
            $table->unsignedBigInteger('barangid');
            $table->double('hpp');
            $table->double('hargajual');
            $table->unsignedBigInteger('userid');
            $table->timestamps();
            $table->unique(['nomerid', 'barangid']);
            $table->foreign('nomerid')->references('id')->on('timhd')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('barangid')->references('id')->on('barangs');
            $table->foreign('userid')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timdts');
    }
};
