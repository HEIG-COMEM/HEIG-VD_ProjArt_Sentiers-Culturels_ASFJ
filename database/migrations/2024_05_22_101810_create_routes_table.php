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
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->text('description');
            $table->integer('duration');
            $table->integer('length');

            $table->decimal('start_lat', 8, 6);
            $table->decimal('start_long', 9, 6);
            $table->decimal('end_lat', 8, 6);
            $table->decimal('end_long', 9, 6);

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
