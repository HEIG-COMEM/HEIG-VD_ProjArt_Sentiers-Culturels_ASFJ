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
        Schema::create('routes_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained()->onUpdate('restrict')->onDelete('restrict');
            $table->foreignId('user_id')->constrained()->onUpdate('restrict')->onDelete('restrict');

            $table->timestamp('start_timestamp')->useCurrent();
            $table->timestamp('end_timestamp')->default(null)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes_history');
    }
};
