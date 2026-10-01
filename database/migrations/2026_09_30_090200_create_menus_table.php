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
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('location_code', 20)->default('main_nav');
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('label', 60);
            $table->string('target_type', 10);
            $table->string('target_value');
            $table->unsignedSmallInteger('order')->default(0);
            $table->boolean('is_active')->default(1);
            $table->unsignedInteger('creator_id');
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('menus')->onDelete('restrict');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
