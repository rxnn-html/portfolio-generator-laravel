<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasUuids;

    protected $table = 'education';
    public $timestamps = false; // these tables have no created_at / updated_at

    protected $fillable = ['portfolio_id', 'school', 'degree', 'year', 'description'];
}