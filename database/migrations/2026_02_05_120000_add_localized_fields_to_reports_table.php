<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            if (!Schema::hasColumn('reports', 'tracking_code')) {
                $table->string('tracking_code')->unique()->nullable()->after('id');
            }
            if (!Schema::hasColumn('reports', 'nama_murid')) {
                $table->string('nama_murid')->nullable()->after('tracking_code');
            }
            if (!Schema::hasColumn('reports', 'email_murid')) {
                $table->string('email_murid')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('reports', 'nama_sekolah')) {
                $table->string('nama_sekolah')->nullable()->after('student_name');
            }
            if (!Schema::hasColumn('reports', 'kelas')) {
                $table->string('kelas')->nullable()->after('nama_sekolah');
            }
            if (!Schema::hasColumn('reports', 'isi_laporan')) {
                $table->text('isi_laporan')->nullable()->after('title');
            }
            if (!Schema::hasColumn('reports', 'jenis_laporan')) {
                $table->string('jenis_laporan')->nullable()->after('isi_laporan');
            }
            if (!Schema::hasColumn('reports', 'guru_id')) {
                $table->unsignedBigInteger('guru_id')->nullable()->after('status');
            }
        });

        // Copy existing data where possible if the old columns exist
        if (Schema::hasColumn('reports', 'code')) {
            DB::statement("UPDATE reports SET tracking_code = code WHERE tracking_code IS NULL AND code IS NOT NULL");
        }
        if (Schema::hasColumn('reports', 'student_name')) {
            DB::statement("UPDATE reports SET nama_murid = student_name WHERE nama_murid IS NULL AND student_name IS NOT NULL");
        }
        if (Schema::hasColumn('reports', 'email')) {
            DB::statement("UPDATE reports SET email_murid = email WHERE email_murid IS NULL AND email IS NOT NULL");
        }
        if (Schema::hasColumn('reports', 'school')) {
            DB::statement("UPDATE reports SET nama_sekolah = school WHERE nama_sekolah IS NULL AND school IS NOT NULL");
        }
        if (Schema::hasColumn('reports', 'class')) {
            DB::statement("UPDATE reports SET kelas = `class` WHERE kelas IS NULL AND `class` IS NOT NULL");
        }
        if (Schema::hasColumn('reports', 'description')) {
            DB::statement("UPDATE reports SET isi_laporan = description WHERE isi_laporan IS NULL AND description IS NOT NULL");
        }
    }

    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            // Drop foreign key if it exists
            try {
                $table->dropForeign('reports_guru_id_foreign');
            } catch (\Exception $e) {
                // Foreign key might not exist
            }
            
            if (Schema::hasColumn('reports', 'tracking_code')) {
                $table->dropColumn('tracking_code');
            }
            if (Schema::hasColumn('reports', 'nama_murid')) {
                $table->dropColumn('nama_murid');
            }
            if (Schema::hasColumn('reports', 'email_murid')) {
                $table->dropColumn('email_murid');
            }
            if (Schema::hasColumn('reports', 'nama_sekolah')) {
                $table->dropColumn('nama_sekolah');
            }
            if (Schema::hasColumn('reports', 'kelas')) {
                $table->dropColumn('kelas');
            }
            if (Schema::hasColumn('reports', 'isi_laporan')) {
                $table->dropColumn('isi_laporan');
            }
            if (Schema::hasColumn('reports', 'jenis_laporan')) {
                $table->dropColumn('jenis_laporan');
            }
            if (Schema::hasColumn('reports', 'guru_id')) {
                $table->dropColumn('guru_id');
            }
        });
    }
};