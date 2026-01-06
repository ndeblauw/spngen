<?php

use App\Models\Activity;
use App\Models\Event;
use App\Models\Reservation;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;

test('DatabaseSeeder creates correct number of users', function () {
    $this->seed(DatabaseSeeder::class);
    
    expect(User::count())->toBe(50);
});

test('DatabaseSeeder creates correct number of activities', function () {
    $this->seed(DatabaseSeeder::class);
    
    expect(Activity::count())->toBe(15);
});

test('DatabaseSeeder creates events for each activity', function () {
    $this->seed(DatabaseSeeder::class);
    
    $activities = Activity::all();
    
    foreach ($activities as $activity) {
        $eventCount = $activity->events()->count();
        expect($eventCount)->toBeGreaterThanOrEqual(2);
        expect($eventCount)->toBeLessThanOrEqual(10);
    }
});

test('DatabaseSeeder creates reservations for each event', function () {
    $this->seed(DatabaseSeeder::class);
    
    $events = Event::all();
    
    foreach ($events as $event) {
        $reservationCount = $event->reservations()->count();
        expect($reservationCount)->toBeGreaterThanOrEqual(2);
        expect($reservationCount)->toBeLessThanOrEqual(20);
    }
});

test('DatabaseSeeder creates valid relationships', function () {
    $this->seed(DatabaseSeeder::class);
    
    // Check that all reservations have valid event and user IDs
    $reservations = Reservation::all();
    
    foreach ($reservations as $reservation) {
        expect($reservation->event)->toBeInstanceOf(Event::class);
        expect($reservation->user)->toBeInstanceOf(User::class);
    }
    
    // Check that all events have valid activity IDs
    $events = Event::all();
    
    foreach ($events as $event) {
        expect($event->activity)->toBeInstanceOf(Activity::class);
    }
});
