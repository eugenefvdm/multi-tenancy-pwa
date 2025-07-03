<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('latest_tenant_id')->nullable()->constrained('tenants')->after('id');
            $table->string('google_id')->nullable()->after('latest_tenant_id');
        });
    }
};