<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @Project
 */
class Project extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'description',
        'project_manager',
        'icon',
        'accent_color'
    ];

    /**
     * @return BelongsTo
     */
    public function projectManager()
    {
        return $this->belongsTo(User::class, 'project_manager');
    }

    /**
     * @return BelongsTo
     */
    public function icon()
    {
        return $this->belongsTo(Picture::class, 'icon');
    }

    /**
     * @return HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'project');
    }
}
