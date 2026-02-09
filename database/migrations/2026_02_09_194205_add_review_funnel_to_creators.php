<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('creators', function (Blueprint $table) {
            $table->integer('review_threshold')->default(4)->after('show_branding');
            $table->string('google_review_url')->nullable()->after('review_threshold');
            $table->json('redirect_platforms')->nullable()->after('google_review_url');
            $table->boolean('prefill_enabled')->default(false)->after('redirect_platforms');
            $table->text('review_prompt_text')->nullable()->after('prefill_enabled');
            $table->text('private_feedback_text')->nullable()->after('review_prompt_text');
        });
    }

    public function down(): void
    {
        Schema::table('creators', function (Blueprint $table) {
            $table->dropColumn([
                'review_threshold',
                'google_review_url',
                'redirect_platforms',
                'prefill_enabled',
                'review_prompt_text',
                'private_feedback_text',
            ]);
        });
    }
};
