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
            // Drop old columns if they exist
            if (Schema::hasColumn('schools', 'secret_formula')) {
                $table->dropColumn('secret_formula');
            }
            if (Schema::hasColumn('schools', 'secret_hint')) {
                $table->dropColumn('secret_hint');
            }
            
            // Add new columns
            if (!Schema::hasColumn('schools', 'secret_code')) {
                $table->string('secret_code', 4)->nullable()->comment('Kode 4 digit acak untuk verifikasi murid');
            }
            if (!Schema::hasColumn('schools', 'secret_code_generated_at')) {
                $table->timestamp('secret_code_generated_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            if (Schema::hasColumn('schools', 'secret_code')) {
                $table->dropColumn('secret_code');
            }
            if (Schema::hasColumn('schools', 'secret_code_generated_at')) {
                $table->dropColumn('secret_code_generated_at');
            }
        });
    }
};
