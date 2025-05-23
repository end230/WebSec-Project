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
        if (!Schema::hasTable('product_comments')) {
            Schema::create('product_comments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->unsignedBigInteger('user_id');
                $table->unsignedInteger('rating');
                $table->text('comment');
                $table->boolean('is_verified_purchase')->default(false);
                $table->boolean('is_approved')->default(true);
                $table->timestamp('approved_at')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->timestamps();

                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
                
                $table->index(['product_id', 'is_approved']);
                $table->index('user_id');
                $table->index('rating');
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_comments');
    }
}; 