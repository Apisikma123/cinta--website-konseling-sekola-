<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('student_name')->nullable();
            $table->string('school');
            $table->string('class');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('title');
            $table->text('description');
            $table->enum('status', ['baru', 'diproses', 'selesai'])->default('baru');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
};