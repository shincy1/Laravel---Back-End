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
        Schema::create('penyewaan_detail', function (Blueprint $table) {
            $table->id('penyewaan_detail_id');
            $table->foreignId('penyewaan_detail_penyewaan_id')->constrained('penyewaan','penyewaan_id')->nullable(false);
            $table->foreignId('penyewaan_detail_alat_id')->constrained('alat','alat_id')->nullable(false);
            $table->integer('penyewaan_detail_jumlah')->nullable(false);
            $table->integer('penyewaan_detail_subharga')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyewaan_detail');
    }
};
