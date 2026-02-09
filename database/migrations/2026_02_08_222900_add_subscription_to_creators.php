<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('creators', function (Blueprint $table) {
            // Subscription fields
            $table->string('stripe_customer_id')->nullable()->after('user_id');
            $table->string('stripe_subscription_id')->nullable()->after('stripe_customer_id');
            $table->string('plan')->default('free')->after('stripe_subscription_id'); // free, pro, business
            $table->string('subscription_status')->default('inactive')->after('plan'); // active, past_due, canceled, inactive
            $table->timestamp('subscription_ends_at')->nullable()->after('subscription_status');
            $table->timestamp('trial_ends_at')->nullable()->after('subscription_ends_at');
        });
    }

    public function down(): void
    {
        Schema::table('creators', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_customer_id',
                'stripe_subscription_id',
                'plan',
                'subscription_status',
                'subscription_ends_at',
                'trial_ends_at',
            ]);
        });
    }
};
