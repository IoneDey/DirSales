<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('penjualanhds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('timsetupid');
            $table->string('nota')->unique();
            $table->date('tgljual');
            $table->string('customernama', 150);
            $table->string('customeralamat', 255);
            $table->string('customernotelp');
            $table->string('shareloc', 150);
            $table->string('namasales', 150);
            $table->string('namalock', 150);
            $table->string('namadriver', 150);
            $table->string('pjkolektornota', 150);
            $table->string('pjadminnota', 150);
            $table->string('fotoktp');
            $table->string('fotonota');
            $table->unsignedBigInteger('userid');
            $table->timestamps();
            $table->foreign('timsetupid')->references('id')->on('timsetups');
            $table->foreign('userid')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('penjualan_hds');
    }
};
