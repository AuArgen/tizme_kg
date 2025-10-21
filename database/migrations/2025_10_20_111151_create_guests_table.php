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
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nickname')->nullable();
            $table->string('phone');
            $table->string('info')->nullable();
            $table->unsignedBigInteger('id_user')->index();
            $table->unsignedBigInteger('id_folder')->nullable()->index();
            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_folder')->references('id')->on('folders');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
