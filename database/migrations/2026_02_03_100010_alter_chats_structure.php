<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('chats', function (Blueprint $table) {
            // add report_id only if report_code exists and report_id does not
            if (Schema::hasColumn('chats', 'report_code') && !Schema::hasColumn('chats', 'report_id')) {
                // add report_id and populate from report_code where possible
                $table->foreignId('report_id')->nullable()->after('id')->constrained('reports')->cascadeOnDelete();
            }

            // rename legacy sender enum to sender_type
            if (Schema::hasColumn('chats', 'sender') && !Schema::hasColumn('chats', 'sender_type')) {
                $table->renameColumn('sender', 'sender_type');
            }

            // rename teacher_id to sender_id only if sender_id not exists
            if (Schema::hasColumn('chats', 'teacher_id') && !Schema::hasColumn('chats', 'sender_id')) {
                $table->renameColumn('teacher_id', 'sender_id');
                // add foreign only if column exists and foreign not already defined
                try {
                    $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
                } catch (\Exception $e) {
                    // ignore if foreign key already exists
                }
            }

            // ensure is_read cast
            try {
                $table->boolean('is_read')->default(false)->change();
            } catch (\Exception $e) {
                // ignore change failures (e.g., if column type already matches)
            }

            // create indexes if columns exist and indexes do not already exist
            if (Schema::hasColumn('chats', 'report_id')) {
                try { $table->index('report_id'); } catch (\Exception $e) { /* ignore */ }
            }
            if (Schema::hasColumn('chats', 'sender_type')) {
                try { $table->index('sender_type'); } catch (\Exception $e) { /* ignore */ }
            }
        });

        // NOTE: migration does not attempt to migrate existing report_code values into report_id
    }

    public function down()
    {
        Schema::table('chats', function (Blueprint $table) {
            if (Schema::hasColumn('chats', 'report_id')) {
                $table->dropForeign(['report_id']);
                $table->dropColumn('report_id');
            }

            if (Schema::hasColumn('chats', 'sender_type')) {
                $table->renameColumn('sender_type', 'sender');
            }

            if (Schema::hasColumn('chats', 'sender_id')) {
                $table->dropForeign(['sender_id']);
                $table->dropColumn('sender_id');
            }

            $table->dropIndex(['sender_type']);
            $table->dropIndex(['report_id']);
        });
    }
};