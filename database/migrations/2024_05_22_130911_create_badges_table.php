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
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('icon_path')->default('default.svg');
            $table->string('description')->nullable();
            $table->foreignId('route_id')->nullable()->constrained('routes')->onDelete('set null');
            $table->foreignId('interest_point_id')->nullable()->constrained('interest_points')->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->constrained('badges')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};
