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
        Schema::create('lawenforcement_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique()->index();
            $table->string('nid_front_side')->nullable();
            $table->string('nid_back_side')->nullable();
            $table->string('job_id_card')->nullable();
            $table->longText('address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 10, 8)->nullable();
            $table->boolean('availability')->default(false);
            $table->boolean('approved')->default(false);

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    // id primary_key auto_increment
    // user_id foreign key references users(id)
    // nid_front_side string
    // nid_back_side string
    // job_id_card string nullable
    // address longText nullable
    // latitude decimal(10,8) nullable
    // longitude decimal(10,8) nullable
    // approved boolean default false
    // created_at timestamp
    // updated_at timestamp

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lawenforcement_details');
    }
};
