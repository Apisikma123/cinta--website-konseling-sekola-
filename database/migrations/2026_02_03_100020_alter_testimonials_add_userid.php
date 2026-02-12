<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('testimonials', function (Blueprint $table) {
            if (!Schema::hasColumn('testimonials', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained('users')->after('report_id');
            }

            // add index for is_approved
            $table->index('is_approved');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::table('testimonials', function (Blueprint $table) {
            if (Schema::hasColumn('testimonials', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }

            $table->dropIndex(['is_approved']);
            $table->dropIndex(['created_at']);
        });
    }
};