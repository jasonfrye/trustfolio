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
        Schema::create('widget_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained()->onDelete('cascade');
            $table->string('theme')->default('light'); // light, dark, custom
            $table->string('primary_color')->default('#4f46e5'); // accent color
            $table->string('background_color')->default('#ffffff'); // card background
            $table->string('text_color')->default('#1f2937'); // text color
            $table->string('border_radius')->default('8'); // px
            $table->string('layout')->default('cards');
            $table->integer('limit')->default(10);
            $table->boolean('show_ratings')->default(true);
            $table->boolean('show_avatars')->default(true);
            $table->boolean('show_dates')->default(true);
            $table->integer('minimum_rating')->default(1);
            $table->string('sort_order')->default('recent');
            $table->boolean('show_branding')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('widget_settings');
    }
};
