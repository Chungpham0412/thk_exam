<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    const TABLE_NAME = 'bookings';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable(self::TABLE_NAME)) {
            Schema::create(self::TABLE_NAME, function (Blueprint $table) {
                $table->unsignedBigInteger('booking_id')->autoIncrement();
                $table->unsignedBigInteger('hotel_id')->comment('hotel id');
                $table->string('customer_name', 255)->comment('customer name');
                $table->string('customer_contact', 255)->comment('customer contact');
                $table->timestamp('chekin_time')->comment('checkin time');
                $table->timestamp('checkout_time')->comment('checkout time');
                $table->timestamps();
                $table->foreign('hotel_id')->references('hotel_id')->on('hotels');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
};
