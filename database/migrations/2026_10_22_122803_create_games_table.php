<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image');                        // путь к обложке
            $table->text('description');                    // краткое описание
            $table->string('rules_pdf')->nullable();        // путь к PDF с правилами
            $table->integer('min_players');
            $table->integer('max_players');
            $table->integer('min_age');
            $table->integer('duration_minutes');
            $table->enum('difficulty', ['easy', 'normal', 'hard']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};