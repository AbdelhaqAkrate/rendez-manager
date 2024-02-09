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
        Schema::create('appointement', function (Blueprint $table) {
            $table->id();
            $table->string('sujetRDV');
            $table->unsignedBigInteger('IdUser');
            $table->unsignedBigInteger('idCreanu');

            // Define foreign key constraints
            $table->foreign('IdUser')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('idCreanu')->references('id')->on('creanu')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointement');
    }
};
