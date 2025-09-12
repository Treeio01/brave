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
        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'conference' or 'invite_page'
            $table->string('reference_id'); // conference_id or invite_page ref
            $table->string('platform'); // 'windows' or 'mac'
            $table->string('tag')->nullable();
            $table->text('user_agent')->nullable();
            $table->json('wallets')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downloads');
    }
};