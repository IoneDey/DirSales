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
        Schema::create('kotas', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255);
            $table->unsignedBigInteger('provinsiid');
            $table->unsignedBigInteger('userid');
            $table->timestamps();
            $table->foreign('provinsiid')->references('id')->on('provinsis');
            $table->foreign('userid')->references('id')->on('users');
            $table->unique(['nama', 'provinsiid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kotas');
    }
};
