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
        Schema::create('artworks_exhibits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exhibit_id')->constrained('exhibits', 'exhibit_id')->onDelete('cascade');
            $table->foreignId('post_id')->constrained('posts', 'post_id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artworks_exhibits');
    }
};
