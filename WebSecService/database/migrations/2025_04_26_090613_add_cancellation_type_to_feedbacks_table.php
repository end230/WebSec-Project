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
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->enum('cancellation_type', ['customer', 'employee'])
                  ->nullable()
                  ->after('comments')
                  ->comment('Type of cancellation: customer (self-service) or employee (admin action)');
            
            $table->text('staff_notes')
                  ->nullable()
                  ->after('cancellation_type')
                  ->comment('Internal notes by staff for cancellations or other administrative actions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->dropColumn('cancellation_type');
            $table->dropColumn('staff_notes');
        });
    }
};
