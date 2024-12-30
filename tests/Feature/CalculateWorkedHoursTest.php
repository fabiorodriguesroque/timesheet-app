<?php

use App\Models\TimeEntry;

it('calculates nine working hours for 8:00-17:00 without lunch', function () {
    $timeEntry = TimeEntry::factory()->create([
        'start_time' => '2024-01-08 08:00:00',
        'end_time' => '2024-01-08 17:00:00',
        'lunching_time' => null,
    ]);

    expect($timeEntry->calculateTotalWorkedHours())->toBe('09:00');
});

it('calculates eight working hours when one hour lunch is taken', function () {
    $timeEntry = TimeEntry::factory()->create([
        'start_time' => '2024-01-08 08:00:00',
        'end_time' => '2024-01-08 17:00:00',
        'lunching_time' => '01:00',
    ]);

    expect($timeEntry->calculateTotalWorkedHours())->toBe('08:00');
});

it('handles thirty minute lunch break correctly', function () {
    $timeEntry = TimeEntry::factory()->create([
        'start_time' => '2024-01-08 08:00:00',
        'end_time' => '2024-01-08 17:00:00',
        'lunching_time' => '00:30',
    ]);

    expect($timeEntry->calculateTotalWorkedHours())->toBe('08:30');
});

it('handles overnight shifts correctly', function () {
    $timeEntry = TimeEntry::factory()->create([
        'start_time' => '2024-01-08 22:00:00',
        'end_time' => '2024-01-09 06:00:00',
        'lunching_time' => '00:15',
    ]);

    expect($timeEntry->calculateTotalWorkedHours())->toBe('07:45');
});

it('calculates partial hours correctly', function () {
    $timeEntry = TimeEntry::factory()->create([
        'start_time' => '2024-01-08 08:00:00',
        'end_time' => '2024-01-08 12:30:00',
        'lunching_time' => null,
    ]);

    expect($timeEntry->calculateTotalWorkedHours())->toBe('04:30');
});

it('handles zero hours when start and end times are same', function () {
    $timeEntry = TimeEntry::factory()->create([
        'start_time' => '2024-01-08 08:00:00',
        'end_time' => '2024-01-08 08:00:00',
        'lunching_time' => null,
    ]);

    expect($timeEntry->calculateTotalWorkedHours())->toBe('00:00');
});