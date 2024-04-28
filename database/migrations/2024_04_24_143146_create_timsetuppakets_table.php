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
        Schema::create('timsetuppakets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('timsetupid');
            $table->string('nama', 50);
            $table->double('hargajual', 15, 8);
            $table->unsignedBigInteger('userid');
            $table->timestamps();
            $table->foreign('timsetupid')
                ->references('id')
                ->on('timsetups')
                ->onDelete('cascade');
            $table->foreign('userid')->references('id')->on('users');
            $table->unique(['timsetupid', 'nama']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timsetuppakets');
    }
};
