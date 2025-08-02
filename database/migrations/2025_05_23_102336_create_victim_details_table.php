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
        Schema::create('victim_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique()->index();
            $table->string('nid_front_side')->nullable();
            $table->string('nid_back_side')->nullable();
            $table->string('student_id_card')->nullable();
            $table->longText('address')->nullable();
            $table->string('emergency_contact_1')->nullable();
            $table->string('emergency_contact_2')->nullable();
            $table->string('emergency_contact_3')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    // id primary_key auto_increment
    // user_id foreign key references users(id)
    // nid_front_side string
    // nid_back_side string
    // student_id_card string nullable
    // address longText nullable
    // emergency_contact_1 string nullable
    // emergency_contact_2 string nullable
    // emergency_contact_3 string nullable
    // created_at timestamp
    // updated_at timestamp

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('victim_details');
    }
};
