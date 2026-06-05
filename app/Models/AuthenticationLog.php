<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthenticationLog extends Model
{
    public $timestamps = false; 
    protected $table = 'AuthenticationLogs';
    protected $primaryKey = 'authenticationLogID';
    protected $fillable = [
        'userID', 
        'username', 
        'ipAddress', 
        'userAgent', 
        'remarks', 
        'dateInserted', 
        'status', 
    ];
}

