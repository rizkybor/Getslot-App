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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('client_email')->unique();
            $table->timestamp('client_email_verified_at')->nullable();
            $table->string('client_password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens_clients', function (Blueprint $table) {
            $table->string('client_email')->primary();
            $table->string('client_token');
            $table->timestamp('client_created_at')->nullable();
        });

        Schema::create('sessions_clients', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('client_id')->nullable()->index();
            $table->string('client_ip_address', 45)->nullable();
            $table->text('client_agent')->nullable();
            $table->longText('client_payload');
            $table->integer('client_last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
        Schema::dropIfExists('password_reset_tokens_clients');
        Schema::dropIfExists('sessions_clients');
    }
};
