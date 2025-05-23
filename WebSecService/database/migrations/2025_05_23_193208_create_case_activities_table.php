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
        Schema::create('case_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('customer_service_cases')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Who performed the action
            
            $table->enum('activity_type', [
                'created',
                'assigned',
                'status_changed',
                'priority_changed',
                'comment_added',
                'internal_note_added',
                'resolved',
                'closed',
                'reopened'
            ]);
            
            $table->string('title'); // Short description of activity
            $table->text('description')->nullable(); // Detailed description
            $table->json('metadata')->nullable(); // Store additional data like old/new values
            
            $table->boolean('is_customer_visible')->default(false); // Can customer see this activity?
            $table->boolean('is_system_generated')->default(false); // System vs user action
            
            $table->timestamps();
            
            // Indexes
            $table->index(['case_id', 'created_at']);
            $table->index(['user_id']);
            $table->index(['activity_type']);
            $table->index(['is_customer_visible']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_activities');
    }
};
