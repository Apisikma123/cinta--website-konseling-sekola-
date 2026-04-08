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
            $table->timestamp('deleted_at')->nullable();
            $table->boolean('deleted_for_everyone')->default(false);
            $table->timestamp('edited_at')->nullable();
            $table->text('original_message')->nullable();

            $table->index(['report_id', 'created_at']);
            $table->index(['report_id', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->dropIndex(['report_id', 'created_at']);
            $table->dropIndex(['report_id', 'id']);
            
            $table->dropColumn([
                'deleted_at',
                'deleted_for_everyone',
                'edited_at',
                'original_message'
            ]);
        });
    }
};
