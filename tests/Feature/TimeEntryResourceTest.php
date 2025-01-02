<?php

use App\Filament\Resources\TimeEntryResource;
use App\Models\Project;
use App\Models\TimeEntry;
use App\Models\User;

beforeEach(function() {
    $this->user = User::factory()->create();
});

describe('list time entry resource', function () {
    it('can render the list page', function () {
        $this->actingAs($this->user)
            ->get(TimeEntryResource::getUrl('index'))
            ->assertSuccessful();
    });

    it('shows only his time entries based on his projects', function () {
        $otherUser = User::factory()->create();
        $otherProject = Project::factory()->create(['user_id' => $otherUser->id]);

        $project = Project::factory()->create(['user_id' => $this->user->id]);
        $timeEntry = TimeEntry::factory()->create(['project_id' => $project->id]);

        $this->actingAs($this->user)
            ->get(TimeEntryResource::getUrl('index'))
            ->assertSee($timeEntry->project->name)
            ->assertDontSee($otherProject->name);
    });
});

describe('create time entry resource', function () {

});

