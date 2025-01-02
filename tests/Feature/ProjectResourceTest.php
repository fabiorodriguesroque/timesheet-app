<?php

use App\Filament\Resources\ProjectResource;
use App\Filament\Resources\ProjectResource\Pages\CreateProject;
use App\Models\Project;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Livewire\livewire;

beforeEach(function() {
    $this->user = User::factory()->create();
});

describe('list project resource', function () {
    it('can render the list of projects', function() {
        $this->actingAs($this->user)
            ->get(ProjectResource::getUrl('index'))->assertSuccessful();
    });
    
    it('only shows projects belonging to the authenticated user', function() {
        $otherUser = User::factory()->create();
        $userProject = Project::factory()->create(['user_id' => $this->user->id]);
        $otherUserProject = Project::factory()->create(['user_id' => $otherUser->id]);
    
        $this->actingAs($this->user)
            ->get(ProjectResource::getUrl('index'))
            ->assertSee($userProject->name)
            ->assertDontSee($otherUserProject->name);
    });
    
    it('can see his own projects', function() {
        $project = Project::factory()->create(['user_id' => $this->user->id]);
    
        $this->actingAs($this->user)
            ->get(ProjectResource::getUrl('index'))
            ->assertSeeText($project->name);
    });
});

describe('create project resource', function () {
    it('can render the create project page', function() {
        $this->actingAs($this->user)
            ->get(ProjectResource::getUrl('create'))->assertSuccessful();
    });

    it('can create project', function() {
        $newData = Project::factory()->make();

        Livewire::actingAs($this->user)
            ->test(CreateProject::class)
            ->fillForm([
                'name' => $newData->name,
                'price_per_hour' => $newData->price_per_hour,
                'color' => $newData->color,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

            $this->assertDatabaseHas(Project::class, [
                'user_id' => $this->user->id,
                'name' => $newData->name,
                'price_per_hour' => $newData->price_per_hour * 100,
                'color' => $newData->color,
            ]);
    });
});