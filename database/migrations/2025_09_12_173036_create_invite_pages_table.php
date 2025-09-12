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
        Schema::create('invite_pages', function (Blueprint $table) {
            $table->id();
            $table->string('ref')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('conference_id')->constrained()->onDelete('cascade');
            $table->string('worker_tag')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invite_pages');
    }
};