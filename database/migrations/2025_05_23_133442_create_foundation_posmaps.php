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
        Schema::create('foundation_posmaps', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->index();
            $table->string('slug', 40)->unique();
            $table->enum('scope', ['BADAN', 'DESA', 'KECAMATAN', 'KELURAHAN', 'LKD', 'OPD', 'UPT'])->index()->default('OPD');
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
        Schema::dropIfExists('foundation_posmaps');
    }
};
