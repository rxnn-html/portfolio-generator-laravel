<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    use HasUuids;

    protected $table = 'social_links';
    public $timestamps = false;

    protected $fillable = ['portfolio_id', 'platform', 'url'];
}