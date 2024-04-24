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
        Schema::create('pelanggan_data', function (Blueprint $table) {
            $table->id('pelanggan_data_id');
            $table->foreignId('pelanggan_data_pelanggan_id')->constrained('pelanggan','pelanggan_id')->nullable(false);
            $table->enum('pelanggan_data_jenis',['KTP','SIM'])->nullable(false);
            $table->string('pelanggan_data_file',255)->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggan_data');
    }
};
