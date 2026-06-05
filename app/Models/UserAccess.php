<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAccess extends Model
{
    public $timestamps = false; 
    protected $table = 'UserAccesses';
    protected $primaryKey = 'userAccessID';
    protected $fillable = [
        'userID', 
        'appModuleActionID', 
        'status', 
    ];
}

