<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            // rename columns
            if (Schema::hasColumn('reports', 'code')) {
                $table->renameColumn('code', 'tracking_code');
            }
            if (Schema::hasColumn('reports', 'student_name')) {
                $table->renameColumn('student_name', 'nama_murid');
            }
            if (Schema::hasColumn('reports', 'school')) {
                $table->renameColumn('school', 'nama_sekolah');
            }
            if (Schema::hasColumn('reports', 'class')) {
                $table->renameColumn('class', 'kelas');
            }
            if (Schema::hasColumn('reports', 'email')) {
                $table->renameColumn('email', 'email_murid');
            }
            if (Schema::hasColumn('reports', 'description')) {
                $table->renameColumn('description', 'isi_laporan');
            }

            // add guru_id
            if (!Schema::hasColumn('reports', 'guru_id')) {
                $table->foreignId('guru_id')->nullable()->constrained('users')->after('status');
            }

            // change status enum to match spec
            // Note: altering enum requires raw SQL for some DBs; we'll use string and constrain via app-level constants
            $table->string('status')->default('baru')->change();

            // add indexes
            $table->index('tracking_code');
            $table->index('guru_id');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'tracking_code')) {
                $table->renameColumn('tracking_code', 'code');
            }
            if (Schema::hasColumn('reports', 'nama_murid')) {
                $table->renameColumn('nama_murid', 'student_name');
            }
            if (Schema::hasColumn('reports', 'nama_sekolah')) {
                $table->renameColumn('nama_sekolah', 'school');
            }
            if (Schema::hasColumn('reports', 'kelas')) {
                $table->renameColumn('kelas', 'class');
            }
            if (Schema::hasColumn('reports', 'email_murid')) {
                $table->renameColumn('email_murid', 'email');
            }
            if (Schema::hasColumn('reports', 'isi_laporan')) {
                $table->renameColumn('isi_laporan', 'description');
            }

            if (Schema::hasColumn('reports', 'guru_id')) {
                $table->dropForeign(['guru_id']);
                $table->dropColumn('guru_id');
            }

            $table->string('status')->default('pending')->change();

            $table->dropIndex(['tracking_code']);
            $table->dropIndex(['guru_id']);
            $table->dropIndex(['status']);
        });
    }
};