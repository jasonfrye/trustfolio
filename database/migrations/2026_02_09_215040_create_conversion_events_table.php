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
        Schema::create('conversion_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained()->cascadeOnDelete();
            $table->string('event_type'); // 'limit_warning', 'limit_reached', 'upgrade_click', 'conversion'
            $table->string('source')->nullable(); // 'dashboard_alert', 'widget_settings', 'pricing_page', etc.
            $table->json('metadata')->nullable(); // Additional context data
            $table->timestamps();

            $table->index(['creator_id', 'event_type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversion_events');
    }
};
