<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('phone', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('model_name');
            $table->integer('mobile_weight');
            $table->integer('ram');
            $table->string('front_camera');
            $table->string('back_camera');
            $table->string('processor');
            $table->integer('battery_capacity');
            $table->string('screen_size');
            $table->integer('launched_year');
            $table->bigInteger('price');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phone');
    }
};
