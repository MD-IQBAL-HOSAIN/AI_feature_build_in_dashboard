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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
               // File uploads
            $table->string('logo')->nullable();
            $table->string('mini_logo')->nullable();
            $table->string('icon')->nullable();

            // Site info
            $table->string('site_title', 255);
            $table->string('app_name', 255);
            $table->string('admin_name', 255)->nullable();

            // Footer info
            $table->string('copyright', 255)->nullable();
            $table->string('contact', 20)->nullable();
            $table->string('email', 255)->nullable();

            // About section (longer text)
            $table->text('about')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
