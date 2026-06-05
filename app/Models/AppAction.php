<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppAction extends Model
{
    public $timestamps = false; 
    protected $table = 'AppActions';
    protected $primaryKey = 'appActionID';
    protected $fillable = [
        'appID', 
        'name', 
        'rank', 
    ];
}

