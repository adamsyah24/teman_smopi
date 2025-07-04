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
        Schema::create('petaks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('x');
            $table->float('y');
            $table->float('width')->default(80);
            $table->float('height')->default(50);
            $table->unsignedTinyInteger('golongan')->default(1); // untuk filter golongan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petaks');
    }
};
