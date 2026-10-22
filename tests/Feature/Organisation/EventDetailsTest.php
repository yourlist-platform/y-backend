<?php

use App\Enum\EventStatusEnum;
use App\Enum\PlatformSettingKeyEnum;
use App\Models\Event;
use App\Models\Organisation;
use App\Models\OrganiserStaff;
use App\Models\PlatformSetting;
use App\Models\User;
use App\Support\UniqueIdGenerator;

test('event details includes the booking platform fee', function () {
    $user = User::factory()->create();
    $organisation = Organisation::factory()->create([
        'owner_id' => $user->id,
        'approve_status' => true,
    ]);
    OrganiserStaff::factory()->for($organisation)->for($user)->create();

    $event = Event::query()->create([
        'organisation_id' => $organisation->id,
        'unique_id' => UniqueIdGenerator::generate('EVT'),
        'name' => 'Platform Fee Event',
        'status' => EventStatusEnum::DRAFT,
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);

    PlatformSetting::query()->updateOrCreate(
        ['key' => PlatformSettingKeyEnum::BOOKING_PLATFORM_FEES_PERCENTAGE->value],
        ['value' => 7],
    );

    $token = test()->postJson('/api/organisation/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->json('data.access_token');

    test()->withToken($token)
        ->getJson('/api/organisation/event/details/'.$event->id)
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.id', $event->id)
        ->assertJsonPath('data.name', 'Platform Fee Event')
        ->assertJsonPath('data.platform_fee', 7);
});

test('event details returns zero platform fee when the setting is missing', function () {
    $user = User::factory()->create();
    $organisation = Organisation::factory()->create([
        'owner_id' => $user->id,
        'approve_status' => true,
    ]);
    OrganiserStaff::factory()->for($organisation)->for($user)->create();

    $event = Event::query()->create([
        'organisation_id' => $organisation->id,
        'unique_id' => UniqueIdGenerator::generate('EVT'),
        'name' => 'No Fee Event',
        'status' => EventStatusEnum::DRAFT,
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);

    PlatformSetting::query()
        ->where('key', PlatformSettingKeyEnum::BOOKING_PLATFORM_FEES_PERCENTAGE->value)
        ->delete();

    $token = test()->postJson('/api/organisation/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->json('data.access_token');

    test()->withToken($token)
        ->getJson('/api/organisation/event/details/'.$event->id)
        ->assertOk()
        ->assertJsonPath('data.platform_fee', 0);
});
