<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Event;
use App\Models\Reservation;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 50 users
        User::factory(50)->create();

        // Create 15 activities
        Activity::factory(15)->create()->each(function ($activity) {
            // Create between 2 and 10 events per activity
            $eventCount = rand(2, 10);
            Event::factory($eventCount)->create([
                'activity_id' => $activity->id,
            ])->each(function ($event) {
                // Create between 2 and 20 reservations per event
                $reservationCount = rand(2, 20);
                $users = User::inRandomOrder()->limit($reservationCount)->get();
                
                foreach ($users as $user) {
                    Reservation::factory()->create([
                        'event_id' => $event->id,
                        'user_id' => $user->id,
                    ]);
                }
            });
        });
    }
}
