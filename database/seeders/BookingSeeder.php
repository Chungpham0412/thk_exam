<?php

namespace Database\Seeders;

use App\Models\Hotel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();
        $data = [];

        for ($i = 0; $i < 500; $i++) {
            // Random check-in time (after 2024-01-01)
            $checkin_time = $faker->dateTimeBetween('2025-01-01', '2025-12-31');
            // Random checkout time (must be after check-in time)
            $checkout_time = $faker->dateTimeBetween($checkin_time, $checkin_time->format('Y-m-d') . ' +7 days');

            // hotel_id random is hotel_id from hotels table
            $hotel_id = Hotel::inRandomOrder()->first()->hotel_id;
            $data[] = [
                'hotel_id' => $hotel_id,
                'customer_name' => $faker->name,
                'customer_contact' => $faker->phoneNumber,
                'chekin_time' => $checkin_time->format('Y-m-d H:i:s'),
                'checkout_time' => $checkout_time->format('Y-m-d H:i:s'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('bookings')->insert($data);
    }
}
