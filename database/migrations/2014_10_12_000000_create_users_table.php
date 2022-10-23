<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string("image")->nullable();
            $table->string("birthdate")->nullable();
            $table->boolean("gender")->nullable();
            $table->boolean("receive_offers")->nullable();
            $table->string("addresses")->nullable();
            $table->string("phone")->nullable();
            $table->string("phone2")->nullable();
            $table->string("cards")->nullable();
            $table->string("favourites")->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
