<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ar_objects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('audio_path')->nullable(); // stored in storage/app/public/ar
            $table->string('3d_path')->nullable();    // stored in storage/app/public/ar
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ar_objects');
    }
};
