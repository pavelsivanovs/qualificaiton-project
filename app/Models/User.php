<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @User
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'status',
        'telephone_number',
        'profile_picture',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
//        'remember_token', // todo evaluate if required
    ];

    /**
     * @return BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(UserStatus::class, 'status');
    }

    /**
     * @return BelongsTo
     */
    public function profilePicture()
    {
        return $this->belongsTo(Picture::class, 'profile_picture');
    }

    /**
     * @return HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'author');
    }

    /**
     * @return HasMany
     */
    public function projects()
    {
        return $this->hasMany(Project::class, 'project_manager');
    }

    /**
     * @return HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'assignee');
    }

    /**
     * @return HasOne
     */
    public function userStatusChangeRequest()
    {
        return $this->hasOne(UserStatusChangeRequest::class, 'user');
    }

    /**
     * @return HasOne
     */
    public function userAccountDeactivationRequest()
    {
        return $this->hasOne(UserAccountDeactivationRequest::class, 'user');
    }
}
