<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('atlas_block_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('block_id')->constrained('atlas_blocks')->cascadeOnDelete();
            $table->string('key');
            $table->longText('value')->nullable();
            $table->timestamps();

            $table->unique(['block_id', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('atlas_block_fields');
    }
};
