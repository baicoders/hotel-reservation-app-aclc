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
        //
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('rm_number')->unique();
            $table->string('rm_type');
            $table->decimal('rm_price_per_night', 8, 2);
            $table->string('status')->default('available');
            $table->timestamps();
        });

        Schema::create('rooms_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->string('photo_path');
            $table->timestamps();
        });

        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });

        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->foreignId('guest_id')->constrained('guests')->onDelete('cascade');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade');
            $table->decimal('amount', 8, 2);
            $table->string('payment_method');
            $table->string('status')->default('pending');
            $table->date('payment_date');
            $table->timestamps();
        });

        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2)->default(0);
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('guests');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('amenities');
    }
};
