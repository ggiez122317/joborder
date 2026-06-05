<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppModule extends Model
{
    public $timestamps = false; 
    protected $table = 'AppModules';
    protected $primaryKey = 'appModuleID';
    protected $fillable = [
        'appID', 
        'appModuleGroupID', 
        'name', 
        'rank', 
        'isDefault', 
    ]; 
}

