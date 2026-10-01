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
        Schema::create('running_texts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('content', 255);
            $table->boolean('is_active')->default(1);
            $table->unsignedSmallInteger('order')->default(0);
            $table->unsignedInteger('creator_id');
            $table->timestamps();

            $table->foreign('creator_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('running_texts');
    }
};
