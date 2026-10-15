<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Design extends Model
{
    protected $fillable = ['title', 'description', 'category', 'image', 'featured', 'display_order', 'status'];
    protected function casts(): array { return ['featured' => 'boolean']; }
}
