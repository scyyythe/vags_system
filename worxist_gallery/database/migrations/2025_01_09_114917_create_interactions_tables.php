<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInteractionsTables extends Migration
{
    public function up()
    {
        // Create likes table
        Schema::create('likes', function (Blueprint $table) {
            $table->id('like_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('post_id')->constrained('posts', 'post_id')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['user_id', 'post_id']);
        });

        // Create saved table
        Schema::create('saved', function (Blueprint $table) {
            $table->id('save_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('post_id')->constrained('posts', 'post_id')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['user_id', 'post_id']);
        });

        // Create favorites table
        Schema::create('favorites', function (Blueprint $table) {
            $table->id('favorite_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('post_id')->constrained('posts', 'post_id')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['user_id', 'post_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('favorites');
        Schema::dropIfExists('saved');
        Schema::dropIfExists('likes');
    }
}
