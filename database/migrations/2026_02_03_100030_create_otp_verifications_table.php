<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('otp_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('email');
            $table->string('otp_code', 6);
            $table->boolean('is_used')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index('email');
            $table->index('otp_code');
        });
    }

    public function down()
    {
        Schema::dropIfExists('otp_verifications');
    }
};