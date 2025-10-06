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
        if (Schema::hasTable('conferences')) {
            return;
        }

        Schema::create('conferences', function (Blueprint $table) {
            $table->id();
            $table->string('invite_code')->unique();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('worker_tag')->nullable();
            $table->unsignedBigInteger('worker_id')->nullable();
            $table->string('domain')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('worker_id')->references('id')->on('workers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conferences');
    }
};
