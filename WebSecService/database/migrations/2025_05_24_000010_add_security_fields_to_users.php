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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('two_factor_enabled')->default(false)->after('theme_color');
            $table->string('recovery_email')->nullable()->after('two_factor_enabled');
            $table->boolean('security_questions_set')->default(false)->after('recovery_email');
            $table->timestamp('last_login_at')->nullable()->after('security_questions_set');
            $table->timestamp('password_changed_at')->nullable()->after('last_login_at');
            $table->timestamp('last_security_audit')->nullable()->after('password_changed_at');
            $table->string('phone')->nullable()->after('last_security_audit');
            $table->text('address')->nullable()->after('phone');
            $table->date('date_of_birth')->nullable()->after('address');
            $table->string('profile_picture')->nullable()->after('date_of_birth');
            $table->text('bio')->nullable()->after('profile_picture');
            $table->json('social_links')->nullable()->after('bio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'two_factor_enabled',
                'recovery_email',
                'security_questions_set',
                'last_login_at',
                'password_changed_at',
                'last_security_audit',
                'phone',
                'address',
                'date_of_birth',
                'profile_picture',
                'bio',
                'social_links'
            ]);
        });
    }
}; 