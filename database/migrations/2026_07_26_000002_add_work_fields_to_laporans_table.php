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
        Schema::table('laporans', function (Blueprint $table) {
            $table->date('tanggal_pengerjaan')->nullable()->after('tanggal');
            $table->text('detail_pengerjaan')->nullable()->after('deskripsi');
            $table->string('nama_tukang')->nullable()->after('detail_pengerjaan');
            $table->string('estimasi')->nullable()->after('nama_tukang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropColumn(['tanggal_pengerjaan', 'detail_pengerjaan', 'nama_tukang', 'estimasi']);
        });
    }
};
