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
        Schema::create('foundation_biodatas', function (Blueprint $table) {
            /**
             * type
             * =====
             * 'ASN'
             * 'LKD'
             * 'OPD'
             * 'SPEAKER'
             * 'BENEFICIARY'
             */

            /**
            * role
            * =====
            * 'OPERATOR'   =>
            * 'MEMBER'     =>
            * 'CHAIRMAN'   =>
            * 'MODERATOR'  =>
            * 'FELLOW'     =>
            * 'SPEAKER'    =>
            */

            $table->id();
            $table->string('name', 150);
            $table->string('slug', 18)->unique()->nullable();   // NIK
            $table->string('phone', 16)->unique()->nullable();
            $table->date('birthdate')->index()->nullable();
            $table->enum('kind', ['ASN', 'NON-ASN'])->index()->default('ASN');
            $table->string('type')->index()->default('ASN');
            $table->string('role', 50)->index()->nullable();
            $table->foreignId('gender_id')->nullable();
            $table->foreignId('faith_id')->nullable();
            $table->foreignId('position_id')->nullable();
            $table->morphs('workunitable');
            $table->text('address')->nullable();
            $table->foreignId('village_id')->nullable();
            $table->foreignId('subdistrict_id')->nullable();
            $table->foreignId('regency_id')->nullable();
            $table->string('citizen')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('family_card_number')->index()->nullable();      // KK
            $table->enum('scope', ['PENDIDIKAN', 'KESEHATAN', 'PEKERJAAN-UMUM', 'PERUMAHAN-RAKYAT', 'TRANTIB', 'SOSIAL'])->index()->nullable();
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
        Schema::dropIfExists('foundation_biodatas');
    }
};
