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
        Schema::table('game_stamps', function (Blueprint $table) {
            $table->string('type')->after('id'); // misal: 'quiz', 'mission', dll
            $table->unsignedInteger('passing_score')->default(0)->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_stamps', function (Blueprint $table) {
            $table->dropColumn(['type', 'passing_score']);
        });
    }
};
