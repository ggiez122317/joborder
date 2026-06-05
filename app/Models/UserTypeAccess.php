<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTypeAccess extends Model
{
    public $timestamps = false; 
    protected $table = 'UserTypeAccesses';
    protected $primaryKey = 'userTypeAccessID';
    protected $fillable = [
        'userTypeID', 
        'appModuleActionID', 
        'status', 
    ];
}

