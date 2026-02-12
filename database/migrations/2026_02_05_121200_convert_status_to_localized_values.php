<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Map existing english statuses to localized ones
        if (Schema::hasColumn('reports', 'status')) {
            DB::statement("UPDATE reports SET status = 'baru' WHERE status = 'pending'");
            DB::statement("UPDATE reports SET status = 'diproses' WHERE status = 'in_progress'");
            DB::statement("UPDATE reports SET status = 'selesai' WHERE status = 'completed'");
            // Leave 'rejected' as-is or map if desired

            // Change column type to string to allow any localized values
            Schema::table('reports', function (Blueprint $table) {
                $table->string('status')->default('baru')->change();
            });
        }
    }

    public function down()
    {
        // Try to revert to original english statuses if present
        if (Schema::hasColumn('reports', 'status')) {
            DB::statement("UPDATE reports SET status = 'pending' WHERE status = 'baru'");
            DB::statement("UPDATE reports SET status = 'in_progress' WHERE status = 'diproses'");
            DB::statement("UPDATE reports SET status = 'completed' WHERE status = 'selesai'");

            Schema::table('reports', function (Blueprint $table) {
                $table->enum('status', ['pending', 'in_progress', 'completed', 'rejected'])->default('pending')->change();
            });
        }
    }
};