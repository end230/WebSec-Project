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
            $table->string('certificate_cn')->nullable()->after('github_id');
            $table->string('certificate_serial')->nullable()->after('certificate_cn');
            $table->text('certificate_dn')->nullable()->after('certificate_serial');
            $table->timestamp('last_certificate_login')->nullable()->after('certificate_dn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'certificate_cn',
                'certificate_serial', 
                'certificate_dn',
                'last_certificate_login'
            ]);
        });
    }
};
