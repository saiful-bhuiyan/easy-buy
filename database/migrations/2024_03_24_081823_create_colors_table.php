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
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('code');
            $table->tinyInteger('status')->default(1)->comment('1 = Active & 0 = Inactive');
            $table->unsignedBigInteger('user_id')->default(null)->nullable()->comment('Created By');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');;
            $table->date('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colors');
    }
};
