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
        Schema::create('quest_gifs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_quest');
            $table->decimal('price', 9, 2)->default(0);
            $table->unsignedBigInteger('id_user')->index();
            $table->string('info')->nullable();
            $table->text('description')->nullable();
            $table->date('date')->nullable();
            $table->foreign('id_quest')->references('id')->on('guests')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quest_gifs');
    }
};
