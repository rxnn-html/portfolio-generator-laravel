<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasUuids;

    protected $table = 'projects';
    public $timestamps = false;

    protected $fillable = ['portfolio_id', 'project_name', 'description', 'technologies', 'project_link'];
}