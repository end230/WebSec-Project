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
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('email_notifications')->default(true);
            $table->boolean('push_notifications')->default(true);
            $table->boolean('sms_notifications')->default(false);
            $table->boolean('marketing_emails')->default(true);
            $table->boolean('security_alerts')->default(true);
            $table->boolean('order_updates')->default(true);
            $table->boolean('product_updates')->default(true);
            $table->boolean('newsletter')->default(true);
            $table->boolean('price_alerts')->default(true);
            $table->boolean('low_balance_alerts')->default(true);
            $table->boolean('login_alerts')->default(true);
            $table->enum('notification_frequency', ['immediate', 'daily', 'weekly', 'monthly'])->default('immediate');
            $table->time('quiet_hours_start')->nullable();
            $table->time('quiet_hours_end')->nullable();
            $table->string('preferred_language')->default('en');
            $table->string('timezone')->default('UTC');
            $table->timestamps();

            // Index for faster user lookups
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
}; 