<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('swdklljs', function (Blueprint $table) {

            $table->enum('metode_pembayaran', [
                'online',
                'konvensional'
            ])->nullable()->after('status_bayar');

        });
    }

    public function down(): void
    {
        Schema::table('swdklljs', function (Blueprint $table) {

            $table->dropColumn('metode_pembayaran');

        });
    }
};