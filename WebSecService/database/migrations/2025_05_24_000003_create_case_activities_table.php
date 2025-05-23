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
        if (!Schema::hasTable('case_activities')) {
            Schema::create('case_activities', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('case_id');
                $table->unsignedBigInteger('user_id');
                $table->enum('activity_type', [
                    'created', 'assigned', 'status_changed', 'priority_changed', 
                    'comment_added', 'internal_note_added', 'resolved', 'closed', 'reopened'
                ]);
                $table->string('title');
                $table->text('description')->nullable();
                $table->json('metadata')->nullable();
                $table->boolean('is_customer_visible')->default(false);
                $table->boolean('is_system_generated')->default(false);
                $table->timestamps();

                $table->foreign('case_id')->references('id')->on('customer_service_cases')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                
                $table->index(['case_id', 'created_at']);
                $table->index('user_id');
                $table->index('activity_type');
                $table->index('is_customer_visible');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_activities');
    }
}; 