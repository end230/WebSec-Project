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
        Schema::create('security_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('performed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action');
            $table->enum('category', [
                'authentication',
                'authorization',
                'data_access',
                'profile_change',
                'security_settings',
                'role_permission',
                'credit_transaction'
            ]);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('completed');
            $table->enum('risk_level', ['low', 'medium', 'high', 'critical'])->default('low');
            $table->text('description');
            $table->timestamps();

            // Indexes for faster queries
            $table->index(['user_id', 'category', 'created_at']);
            $table->index(['user_id', 'risk_level', 'created_at']);
            $table->index(['performed_by', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_audits');
    }
}; 