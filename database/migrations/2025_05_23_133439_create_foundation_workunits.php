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
        Schema::create('foundation_workunits', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('slug', 40)->unique();
            $table->enum('scope', ['OPD', 'UPT', 'KECAMATAN', 'KELURAHAN', 'DESA'])->index()->default('OPD');
            $table->foreignId('village_id')->nullable();
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
        Schema::dropIfExists('foundation_workunits');
    }
};
