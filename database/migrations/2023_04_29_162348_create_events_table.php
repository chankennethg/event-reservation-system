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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->uuid('user_uuid')->comment('Event user creator');
            $table->string('title');
            $table->longText('description');
            $table->string('location');
            $table->decimal('price', 8, 2);
            $table->unsignedInteger('attendee_limit')->nullable()->comment('No limit when null');
            $table->dateTime('starts_at')->comment('Event Date & Time Schedule');
            $table->dateTime('ends_at')->comment('Event Date & Time Schedule');
            $table->dateTime('reservation_starts_at');
            $table->dateTime('reservation_ends_at');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();

            $table->foreign('user_uuid')->references('uuid')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
