<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('swdklljs', function (Blueprint $table) {
            $table->id();

            $table->integer('no')->nullable();

            $table->string('id_petugas')->nullable();

            $table->string('nama_ric')->nullable();

            $table->string('nopol')->nullable();

            $table->string('nama_wp')->nullable();

            $table->text('alamat')->nullable();

            $table->date('masa_berlaku')->nullable();

            $table->string('gol')->nullable();

            $table->string('no_hp')->nullable();

            $table->string('status_penyerahan')->nullable();

            $table->string('status_kepemilikan')->nullable();

            $table->string('status_bayar')->nullable();

            $table->decimal('nominal_bayar', 15, 2)->nullable();

            $table->string('source_file')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('swdklljs');
    }
};