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
        Schema::create('exhibits', function (Blueprint $table) {
            $table->id('exhibit_id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('exhibit_title');
            $table->text('exhibit_description');
            $table->date('exhibit_date');
            $table->string('exhibit_type');
            $table->string('exhibit_status')->default('Pending');
            $table->timestamp('accepted_at')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exhibits');
    }
};
