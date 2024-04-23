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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(null)->nullable()->comment('Created By');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('post_code')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('order_notes')->nullable();
            $table->string('cuppon_code')->nullable();
            $table->double('discount_amount')->nullable();
            $table->double('shipping_charge')->nullable();
            $table->double('total_amount')->nullable();
            $table->string('payment_method')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('is_payment')->default(0);
            $table->text('payment_data')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('stripe_session_id')->nullable();
            $table->date('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
