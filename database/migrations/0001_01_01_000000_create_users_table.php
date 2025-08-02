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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique()->index();
            $table->string('phone')->nullable();
            $table->string('email')->unique()->index();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('type')->default(1)->comment('0 = admin, 1 = user, 2 = volunteer, 3 = law enforcement');
            $table->boolean('is_verified')->default(false)->comment('0 = not verified, 1 = verified');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    // id primary_key auto_increment
    // name string
    // username string unique
    // phone string nullable
    // email string unique
    // email_verified_at timestamp nullable
    // password string
    // type integer default 1
    // is_verified boolean default false
    // created_at timestamp
    // updated_at timestamp

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
