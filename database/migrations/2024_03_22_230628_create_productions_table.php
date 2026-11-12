<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->json('searched_terms');
            $table->string('repository', 32);
            $table->string('type', 24);
            $table->string('language', 16);
            $table->text('title');
            $table->year('year');
            $table->string('institution')->nullable();
            $table->string('program')->nullable();
            $table->string('periodical')->nullable();
            $table->string('city')->nullable();
            $table->foreignId('state_id')->nullable();
            $table->string('url')->nullable();
            $table->string('doi')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productions');
    }
};
