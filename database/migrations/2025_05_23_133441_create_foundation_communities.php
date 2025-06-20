<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('foundation_communities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->index();
            $table->string('slug', 40)->unique();
            $table->foreignId('communitymap_id');
            $table->foreignId('workunit_id');
            $table->foreignId('village_id');
            $table->foreignId('subdistrict_id');
            $table->foreignId('regency_id');
            $table->foreignId('officer_id')->nullable();
            $table->string('citizen')->nullable();
            $table->string('neighborhood')->nullable();
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
        Schema::dropIfExists('foundation_communities');
    }
};
