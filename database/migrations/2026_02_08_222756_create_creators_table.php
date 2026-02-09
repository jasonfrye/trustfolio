<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('collection_url')->unique(); // unique URL for testimonial collection
            $table->string('display_name')->nullable();
            $table->string('website')->nullable();
            $table->string('avatar_url')->nullable();
            // Widget branding settings
            $table->string('widget_theme')->default('light'); // light, dark, custom
            $table->string('primary_color')->nullable(); // hex color for custom theme
            $table->string('font_family')->nullable();
            $table->boolean('show_branding')->default(true); // "Powered by TrustFolio"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creators');
    }
};
