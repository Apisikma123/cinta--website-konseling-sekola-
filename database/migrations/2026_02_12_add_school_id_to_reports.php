<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            // Tambah school_id foreign key jika belum ada
            if (!Schema::hasColumn('reports', 'school_id')) {
                $table->unsignedBigInteger('school_id')->nullable()->after('id');
                $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'school_id')) {
                $table->dropForeign(['school_id']);
                $table->dropColumn('school_id');
            }
        });
    }
};
