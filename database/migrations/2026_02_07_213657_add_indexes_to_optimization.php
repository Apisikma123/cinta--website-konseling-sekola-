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
        Schema::table('reports', function (Blueprint $table) {
            $table->index('tracking_code');
            $table->index('status');
            $table->index('nama_sekolah');
            $table->index('guru_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
            $table->index('is_approved');
            $table->index('is_active');
            $table->index('email');
        });
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
