<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('atlas_page_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('atlas_pages')->cascadeOnDelete();
            $table->json('snapshot');
            $table->string('label')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('atlas_page_revisions');
    }
};
