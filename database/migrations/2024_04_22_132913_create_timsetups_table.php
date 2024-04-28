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
        Schema::create('timsetups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('timid');
            $table->unsignedBigInteger('kotaid');
            $table->date('tglawal');
            $table->date('tglakhir');
            $table->integer('angsuranhari');
            $table->integer('angsuranperiode');
            $table->string('pic', 255);
            $table->unsignedBigInteger('userid');
            $table->timestamps();
            $table->foreign('userid')->references('id')->on('users');
            $table->foreign('timid')->references('id')->on('tims');
            $table->foreign('kotaid')->references('id')->on('kotas');
            $table->unique(['timid', 'kotaid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timsetups');
    }
};
