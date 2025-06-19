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
        Schema::create('foundation_positions', function (Blueprint $table) {
            $table->id();
            $table->text('name')->index();
            $table->text('slug')->unique();
            $table->foreignId('posmap_id');
            $table->foreignId('workunit_id');
            $table->foreignId('officer_id')->nullable();
            $table->jsonb('meta')->nullable();
            $table->nestedSet();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foundation_positions');
    }
};
