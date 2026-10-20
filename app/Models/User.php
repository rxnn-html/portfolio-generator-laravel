<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasUuids;

    protected $table = 'users';
    protected $fillable = ['google_id', 'name', 'email', 'avatar'];
    protected $hidden = ['remember_token'];

    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class);
    }
}