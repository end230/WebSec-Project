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
        if (!Schema::hasTable('customer_service_cases')) {
            Schema::create('customer_service_cases', function (Blueprint $table) {
                $table->id();
                $table->string('case_number')->unique();
                $table->unsignedBigInteger('product_comment_id');
                $table->unsignedBigInteger('customer_id');
                $table->unsignedBigInteger('product_id');
                $table->unsignedBigInteger('assigned_to')->nullable();
                $table->enum('status', ['open', 'in_progress', 'waiting_customer', 'resolved', 'closed'])->default('open');
                $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
                $table->enum('category', ['product_quality', 'shipping', 'service', 'billing', 'other'])->default('product_quality');
                $table->string('subject')->nullable();
                $table->text('description')->nullable();
                $table->text('resolution')->nullable();
                $table->text('internal_notes')->nullable();
                $table->timestamp('assigned_at')->nullable();
                $table->timestamp('first_response_at')->nullable();
                $table->timestamp('resolved_at')->nullable();
                $table->timestamp('closed_at')->nullable();
                $table->integer('response_time_hours')->nullable();
                $table->integer('resolution_time_hours')->nullable();
                $table->timestamps();

                $table->foreign('product_comment_id')->references('id')->on('product_comments')->onDelete('cascade');
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
                
                $table->index('status');
                $table->index('priority');
                $table->index('assigned_to');
                $table->index('customer_id');
                $table->index('created_at');
                $table->index('case_number');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_service_cases');
    }
}; 