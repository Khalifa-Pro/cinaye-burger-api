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
        Schema::create('burgers', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prix');
            $table->string('image',300);
            $table->string('description');
            $table->integer('archiver');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('burgers');
    }
};
