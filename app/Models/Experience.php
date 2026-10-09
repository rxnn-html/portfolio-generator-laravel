<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasUuids;

    protected $table = 'experiences';
    public $timestamps = false;

    protected $fillable = ['portfolio_id', 'job_title', 'company', 'start_date', 'end_date', 'description'];
}