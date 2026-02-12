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
        Schema::table('chats', function (Blueprint $table) {
            // First drop the foreign key if it exists
            try {
                $table->dropForeign(['sender_id']);
            } catch (\Exception $e) {
                // Ignore if foreign key doesn't exist
            }
            
            // Then make it nullable
            if (Schema::hasColumn('chats', 'sender_id')) {
                $table->unsignedBigInteger('sender_id')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            try {
                $table->unsignedBigInteger('sender_id')->nullable(false)->change();
            } catch (\Exception $e) {
                // Ignore if can't revert
            }
        });
    }
};