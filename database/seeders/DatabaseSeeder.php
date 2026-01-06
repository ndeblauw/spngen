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

        // Get all user IDs once to avoid N+1 queries
        $allUserIds = User::pluck('id')->toArray();

        // Create 15 activities
        Activity::factory(15)->create()->each(function ($activity) use ($allUserIds) {
            // Create between 2 and 10 events per activity
            $eventCount = rand(2, 10);
            Event::factory($eventCount)->create([
                'activity_id' => $activity->id,
            ])->each(function ($event) use ($allUserIds) {
                // Create between 2 and 20 reservations per event
                $reservationCount = rand(2, 20);
                
                // Ensure we don't try to create more reservations than available users
                $reservationCount = min($reservationCount, count($allUserIds));
                
                // Randomly select unique user IDs for this event
                $selectedUserIds = collect($allUserIds)->random($reservationCount)->toArray();
                
                foreach ($selectedUserIds as $userId) {
                    Reservation::factory()->create([
                        'event_id' => $event->id,
                        'user_id' => $userId,
                    ]);
                }
            });
        });
    }
}
