<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('atlas_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('atlas_pages')->cascadeOnDelete();
            $table->string('type');
            $table->string('style')->default('default');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->index(['page_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('atlas_blocks');
    }
};
