<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    /**
     * Get the projects for the user.
     * 
     * @return HasMany<Project>
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get the time entries for the user through the projects.
     * 
     * @return HasManyThrough<TimeEntry>
     */
    public function timeEntries(): HasManyThrough
    {
        return $this->hasManyThrough(TimeEntry::class, Project::class);
    }

    /**
     * Get the payments for the user through the projects.
     * 
     * @return HasManyThrough<Payment>
     */
    public function payments(): HasManyThrough
    {
        return $this->hasManyThrough(TimeEntry::class, Project::class);
    }
}
