<?php

use App\Models\Post;
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
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type_code', 10);
            $table->unsignedInteger('category_id')->nullable();
            $table->string('title', 150);
            $table->string('slug', 180)->unique();
            $table->string('excerpt')->nullable();
            $table->longText('content');
            $table->unsignedTinyInteger('status_id')->default(Post::STATUS_DRAFT);
            $table->timestamp('published_at')->nullable();
            $table->string('meta_title', 180)->nullable();
            $table->string('meta_description')->nullable();
            $table->unsignedInteger('creator_id');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('post_categories')->onDelete('restrict');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
