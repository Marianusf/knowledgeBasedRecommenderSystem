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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model');
            $table->integer('year');
            $table->bigInteger('price');
            $table->string('fuel_type');
            $table->string('transmission');
            $table->string('vehicle_type');
            $table->float('mileage'); // km/liter
            $table->tinyInteger('seats');
            $table->integer('km_driven');
            $table->integer('engine_size');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
