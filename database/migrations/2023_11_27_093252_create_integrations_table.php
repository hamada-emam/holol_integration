<?php

use App\Models\Provider;
use App\Models\User;
use ILLUMINATE\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            // Foreign key for users that have more than one integration types(accurate, imile, ...)
            // will allow this user for sign into the system and handle it's owen integrations
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Provider::class);
            $table->string('provider_url', 255);
            $table->json('provider_secret');
            $table->string('user_token', 1000);
            $table->boolean('active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('integrations');
    }
};