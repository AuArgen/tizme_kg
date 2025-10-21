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
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('info')->nullable();
            $table->unsignedBigInteger('id_parent')->nullable()->index();
            $table->unsignedBigInteger('id_user')->index();
            $table->unique(['name', 'id_parent', 'id_user']);
            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_parent')->references('id')->on('folders');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folders');
    }
};
