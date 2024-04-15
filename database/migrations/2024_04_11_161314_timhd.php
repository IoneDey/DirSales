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
        Schema::create('timhd', function (Blueprint $table) {
            $table->id();
            $table->string('nomer', 6);
            $table->unsignedBigInteger('ptid');
            $table->unsignedBigInteger('kotaid');
            $table->date('tglawal');
            $table->date('tglakhir');
            $table->string('pic', 255);
            $table->unsignedBigInteger('userid');
            $table->timestamps();
            $table->unique(['nomer', 'kotaid']);
            $table->foreign('ptid')->references('id')->on('pts');
            $table->foreign('kotaid')->references('id')->on('kotas');
            $table->foreign('userid')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timhd');
    }
};
