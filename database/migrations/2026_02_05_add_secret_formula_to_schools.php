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
        Schema::table('schools', function (Blueprint $table) {
            if (!Schema::hasColumn('schools', 'secret_formula')) {
                $table->string('secret_formula')->nullable()->comment('Contoh: Tanggal Lahir DDMMYY');
            }
            if (!Schema::hasColumn('schools', 'secret_hint')) {
                $table->string('secret_hint')->nullable()->comment('Hint untuk murid: Contoh: 150605 = 15 Juni 2005');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            if (Schema::hasColumn('schools', 'secret_formula')) {
                $table->dropColumn('secret_formula');
            }
            if (Schema::hasColumn('schools', 'secret_hint')) {
                $table->dropColumn('secret_hint');
            }
        });
    }
};
