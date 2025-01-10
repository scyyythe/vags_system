<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();  // Primary key
            $table->foreignId('user_id')     // The user who will receive the notification
                ->constrained('users')    // Assuming you have a 'users' table
                ->onDelete('cascade');    // Cascade delete if the user is deleted
            $table->string('type');          // Type of notification (e.g., 'artwork_accepted', 'user_followed')
            $table->text('message');         // The notification message
            $table->boolean('is_read')->default(false);  // Whether the notification has been read
            $table->timestamps();            // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
