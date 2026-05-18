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
        Schema::create('attachment_files', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')->unique();

            $table->enum('type', ['image', 'document']);

            $table->string('file_name');

            $table->string('file_name_original');

            $table->string('file_path');

            $table->unsignedBigInteger('file_size');

            $table->string('file_type');

            $table->string('client_session_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachment_files');
    }
};