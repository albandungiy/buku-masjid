<?php

use App\Models\Distribution;
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
        Schema::create('distributions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('book_id');
            $table->unsignedInteger('category_id');
            $table->string('title', 60);
            $table->unsignedDecimal('amount', 12);
            $table->json('asnaf_detail')->nullable();
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('status_id')->default(Distribution::STATUS_PENDING);
            $table->unsignedInteger('transaction_id')->nullable();
            $table->string('distribution_date')->nullable();
            $table->unsignedInteger('creator_id');
            $table->unsignedInteger('approved_id')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('book_id')->references('id')->on('books')->onDelete('restrict');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('restrict');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('approved_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distributions');
    }
};
