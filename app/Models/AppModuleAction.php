<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppModuleAction extends Model
{
    public $timestamps = false; 
    protected $table = 'AppModuleActions';
    protected $primaryKey = 'appModuleActionID';
    protected $fillable = [
        'appModuleID', 
        'appActionID', 
    ];
}

