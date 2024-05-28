<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->default(DB::raw('(UUID())'));
            $table->string('name');
            $table->text('description');
            $table->integer('duration');
            $table->integer('length');

            $table->string('start_lat');
            $table->string('start_long');
            $table->string('end_lat');
            $table->string('end_long');

            $table->json('path')->nullable();

            $table->foreignId('difficulty_id')->constrained()->onDelete('restrict')->onUpdate('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
