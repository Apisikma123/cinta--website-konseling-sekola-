<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            if (!Schema::hasColumn('reports', 'jenis_laporan')) {
                $table->string('jenis_laporan')->nullable()->after('isi_laporan');
            }
        });
    }

    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'jenis_laporan')) {
                $table->dropColumn('jenis_laporan');
            }
        });
    }
};