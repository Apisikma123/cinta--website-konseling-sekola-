<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('name');
            $table->string('school');
            $table->string('class');
            $table->text('message');
            $table->string('unique_code')->nullable()->unique();
            $table->enum('status', ['pending', 'verified'])->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index('email');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
