<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Portfolio extends Model
{
    protected $table = 'portfolios';

    // Only these columns can be filled from a form (blocks mass-assignment tricks)
    protected $fillable = [
        'full_name', 'profile_image', 'email', 'contact_number',
        'address', 'about_me', 'template',
    ];

    public const TEMPLATES = ['simple', 'modern', 'creative'];

    public function education(): HasMany    { return $this->hasMany(Education::class); }
    public function skills(): HasMany       { return $this->hasMany(Skill::class); }
    public function projects(): HasMany     { return $this->hasMany(Project::class); }
    public function experiences(): HasMany  { return $this->hasMany(Experience::class); }
    public function socialLinks(): HasMany  { return $this->hasMany(SocialLink::class); }
}