<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('certificate_serial')->nullable();
            $table->text('certificate_dn')->nullable();
            $table->timestamp('last_certificate_login')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('certificate_serial');
            $table->dropColumn('certificate_dn');
            $table->dropColumn('last_certificate_login');
        });
    }
}; 