<?php

use App\Models\Mosque;
use App\Models\Student;
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
        Schema::create('page_recitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedsmallInteger('start_page');
            $table->unsignedsmallInteger('end_page');
            $table->foreignIdFor(Mosque::class)->constrained()->cascadeOnDelete();

            $table->string('rate')->nullable();
            $table->foreignIdFor(Student::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_recitations');
    }
};
