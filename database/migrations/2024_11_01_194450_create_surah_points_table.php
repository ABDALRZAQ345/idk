<?php

use App\Models\Mosque;
use App\Models\Surah;
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
        Schema::create('surah_points', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Surah::class)->constrained();
            $table->foreignIdFor(Mosque::class)->constrained()->cascadeOnDelete();
            $table->unsignedInteger('points')->default(10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surah_points');
    }
};
