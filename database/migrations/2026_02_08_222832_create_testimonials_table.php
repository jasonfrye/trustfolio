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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained()->onDelete('cascade');
            $table->string('author_name');
            $table->string('author_email')->nullable();
            $table->string('author_title')->nullable();
            $table->string('author_avatar_url')->nullable();
            $table->text('content');
            $table->integer('rating')->default(5);
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->string('source')->nullable(); // email, direct, import
            $table->string('external_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
