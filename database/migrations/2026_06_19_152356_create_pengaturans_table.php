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
        Schema::create('pengaturans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_aplikasi')->default('Arif Renggy Portfolio');
            $table->text('ascii_header')->nullable();
            $table->string('versi')->default('v4.0.1');
            $table->text('analisis_tambahan_arsenal')->nullable();
            $table->text('catatan_pengembang_kontak')->nullable();
            $table->string('waktu_respons_kontak')->default('12-24 Jam Standar Sektor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturans');
    }
};
