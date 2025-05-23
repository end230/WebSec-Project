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
        Schema::create('customer_service_cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_number')->unique(); // Auto-generated case number like CS-2024-001
            $table->foreignId('product_comment_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade'); // The customer who made the comment
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null'); // Customer service rep
            
            $table->enum('status', ['open', 'in_progress', 'waiting_customer', 'resolved', 'closed'])->default('open');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('category', ['product_quality', 'shipping', 'service', 'billing', 'other'])->default('product_quality');
            
            $table->string('subject')->nullable(); // Generated from comment or custom
            $table->text('description')->nullable(); // Additional description if needed
            $table->text('resolution')->nullable(); // How the case was resolved
            $table->text('internal_notes')->nullable(); // Private notes for CS team
            
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('first_response_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            
            // SLA tracking
            $table->integer('response_time_hours')->nullable(); // Time to first response
            $table->integer('resolution_time_hours')->nullable(); // Time to resolution
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['status']);
            $table->index(['priority']);
            $table->index(['assigned_to']);
            $table->index(['customer_id']);
            $table->index(['created_at']);
            $table->index(['case_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_service_cases');
    }
};
