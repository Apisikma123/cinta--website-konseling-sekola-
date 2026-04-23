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
        try {
            Schema::table('reports', function (Blueprint $table) {
                // Tambahkan index satu per satu untuk menghindari crash total kalau satu gagal
            });
            Schema::getConnection()->statement('CREATE INDEX reports_tracking_code_index ON reports (tracking_code)');
        } catch (\Exception $e) {}
        try { Schema::getConnection()->statement('CREATE INDEX reports_status_index ON reports (status)'); } catch (\Exception $e) {}
        try { Schema::getConnection()->statement('CREATE INDEX reports_nama_sekolah_index ON reports (nama_sekolah)'); } catch (\Exception $e) {}
        try { Schema::getConnection()->statement('CREATE INDEX reports_guru_id_index ON reports (guru_id)'); } catch (\Exception $e) {}

        try { Schema::getConnection()->statement('CREATE INDEX users_role_index ON users (role)'); } catch (\Exception $e) {}
        try { Schema::getConnection()->statement('CREATE INDEX users_is_approved_index ON users (is_approved)'); } catch (\Exception $e) {}
        try { Schema::getConnection()->statement('CREATE INDEX users_is_active_index ON users (is_active)'); } catch (\Exception $e) {}
        try { Schema::getConnection()->statement('CREATE INDEX users_email_index ON users (email)'); } catch (\Exception $e) {}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropIndex(['tracking_code']);
            $table->dropIndex(['status']);
            $table->dropIndex(['nama_sekolah']);
            $table->dropIndex(['guru_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['is_approved']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['email']);
        });
    }
};
