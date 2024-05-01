<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('timsetupbarangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('timsetuppaketid');
            $table->unsignedBigInteger('barangid');
            $table->double('hpp', 15, 8);
            $table->unsignedBigInteger('userid');
            $table->timestamps();
            $table->foreign('timsetuppaketid')->references('id')->on('timsetuppakets')->onDelete('cascade');
            $table->foreign('barangid')->references('id')->on('barangs');
            $table->foreign('userid')->references('id')->on('users');
            $table->unique(['timsetuppaketid', 'barangid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('timsetupbarangs');
    }
};
