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
            $table->string('company_code');
            $table->string('client_code');
            $table->string('type_code');
            $table->string('url');
            $table->string('token');
            $table->string('secret_key');
            $table->unique(['client_code', 'type_code']);
            $table->unique(['company_code', 'client_code', 'type_code']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
