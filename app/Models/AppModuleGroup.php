<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppModuleGroup extends Model
{
    public $timestamps = false; 
    protected $table = 'AppModuleGroups';
    protected $primaryKey = 'appModuleGroupID';
    protected $fillable = [
        'name', 
        'rank', 
    ];
}


