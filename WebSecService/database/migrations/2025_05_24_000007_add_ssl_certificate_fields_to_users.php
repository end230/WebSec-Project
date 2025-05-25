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
            $table->string('certificate_serial')->nullable()->after('management_level');
            $table->string('certificate_dn', 1000)->nullable()->after('certificate_serial');
            $table->string('certificate_cn')->nullable()->after('certificate_dn');
            $table->timestamp('last_certificate_login')->nullable()->after('certificate_cn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'certificate_serial',
                'certificate_dn',
                'certificate_cn',
                'last_certificate_login'
            ]);
        });
    }
}; 