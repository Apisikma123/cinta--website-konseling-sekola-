<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->string('report_code'); // karena murid tidak login, pakai kode
            $table->text('message');
            $table->enum('sender', ['student', 'teacher']);
            $table->foreignId('teacher_id')->constrained('users');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chats');
    }
};