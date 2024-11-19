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
        Schema::create('foundation_members', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 16)->unique()->nullable();
            $table->string('phone', 16)->unique();
            $table->foreignId('gender_id')->nullable();
            $table->foreignId('faith_id')->nullable();
            $table->foreignId('position_id')->nullable();
            $table->foreignId('village_id')->nullable();
            $table->foreignId('subdistrict_id')->nullable();
            $table->foreignId('regency_id')->nullable();
            $table->foreignId('community_id')->nullable();
            $table->foreignId('communitymap_id')->nullable();
            $table->string('citizen')->nullable();
            $table->string('neighborhood')->nullable();
            $table->boolean('is_chief')->default(false);
            $table->jsonb('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foundation_members');
    }
};
