<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('url');
            $table->enum('status', ['queued', 'processing', 'done', 'failed'])->default('queued');
            $table->tinyInteger('score')->unsigned()->nullable();
            $table->json('breakdown')->nullable();
            $table->text('summary')->nullable();
            $table->string('fetched_title', 255)->nullable();
            $table->string('fetched_author', 255)->nullable();
            $table->dateTime('fetched_published_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            // Indeksi
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checks');
    }
};

