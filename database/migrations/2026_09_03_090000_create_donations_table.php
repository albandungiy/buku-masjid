<?php

use App\Models\Donation;
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
        Schema::create('donations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('partner_id')->nullable();
            $table->unsignedInteger('book_id');
            $table->string('date')->nullable();
            $table->unsignedDecimal('amount', 12);
            $table->string('payment_method_code', 20);
            $table->unsignedTinyInteger('status_id')->default(Donation::STATUS_PENDING);
            $table->unsignedInteger('net_transaction_id')->nullable();
            $table->unsignedInteger('hak_amil_transaction_id')->nullable();
            $table->unsignedInteger('creator_id');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            $table->foreign('partner_id')->references('id')->on('partners')->onDelete('restrict');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('restrict');
            $table->foreign('net_transaction_id')->references('id')->on('transactions')->onDelete('restrict');
            $table->foreign('hak_amil_transaction_id')->references('id')->on('transactions')->onDelete('restrict');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
