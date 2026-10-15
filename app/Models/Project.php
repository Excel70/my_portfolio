<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'short_description', 'full_description', 'problem', 'solution', 'features', 'role', 'category', 'thumbnail', 'github_url', 'live_url', 'featured', 'status', 'display_order'];
    protected function casts(): array { return ['features' => 'array', 'featured' => 'boolean']; }
    public function technologies(): BelongsToMany { return $this->belongsToMany(Technology::class); }
    public function images(): HasMany { return $this->hasMany(ProjectImage::class)->orderBy('display_order'); }
}
