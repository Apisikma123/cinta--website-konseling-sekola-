<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // NULL = belum verif magic link (ada email tapi belum diklik)
            // timestamp = sudah diklik / tidak pakai email (auto-verified)
            $table->timestamp('email_verified_at')->nullable()->after('email_murid');
        });

        // Backfill: laporan lama yang sudah ada dianggap sudah terverifikasi
        DB::table('reports')->update(['email_verified_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn('email_verified_at');
        });
    }
};
