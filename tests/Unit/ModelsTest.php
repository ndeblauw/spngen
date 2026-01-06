<?php

use App\Models\Activity;
use App\Models\Event;
use App\Models\Reservation;
use App\Models\User;

test('Activity model has correct fillable attributes', function () {
    $activity = Activity::factory()->make();
    expect($activity)->toHaveKey('title');
});

test('Activity can have events', function () {
    $activity = Activity::factory()->create();
    Event::factory()->create(['activity_id' => $activity->id]);
    
    expect($activity->events)->toHaveCount(1);
    expect($activity->events->first())->toBeInstanceOf(Event::class);
});

test('Event belongs to an activity', function () {
    $activity = Activity::factory()->create();
    $event = Event::factory()->create(['activity_id' => $activity->id]);
    
    expect($event->activity)->toBeInstanceOf(Activity::class);
    expect($event->activity->id)->toBe($activity->id);
});

test('Event can have reservations', function () {
    $event = Event::factory()->create();
    Reservation::factory()->create(['event_id' => $event->id]);
    
    expect($event->reservations)->toHaveCount(1);
    expect($event->reservations->first())->toBeInstanceOf(Reservation::class);
});

test('Reservation belongs to an event and a user', function () {
    $event = Event::factory()->create();
    $user = User::factory()->create();
    $reservation = Reservation::factory()->create([
        'event_id' => $event->id,
        'user_id' => $user->id,
    ]);
    
    expect($reservation->event)->toBeInstanceOf(Event::class);
    expect($reservation->event->id)->toBe($event->id);
    expect($reservation->user)->toBeInstanceOf(User::class);
    expect($reservation->user->id)->toBe($user->id);
});

test('User can have reservations', function () {
    $user = User::factory()->create();
    Reservation::factory()->create(['user_id' => $user->id]);
    
    expect($user->reservations)->toHaveCount(1);
    expect($user->reservations->first())->toBeInstanceOf(Reservation::class);
});
