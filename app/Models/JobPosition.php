<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPosition extends Model
{
    public $timestamps = false; 
    protected $table = 'JobPositions';
    protected $primaryKey = 'jobPositionID';
    protected $fillable = ['name', 'description'];
}

