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
        Schema::create('incident_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 10, 8);
            $table->longText('description')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    // id primary_key auto_increment
    // user_id foreign key references users(id)
    // latitude decimal(10,8)
    // longitude decimal(10,8)
    // description longText nullable
    // created_at timestamp
    // updated_at timestamp

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_histories');
    }
};
