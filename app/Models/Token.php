<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    public $timestamps = false; 
    protected $table = 'tokens';
    protected $primaryKey = 'tokenID';
    protected $fillable = [
        'userID', 
        'username', 
        'deviceFingerprint', 
        'token', 
        'dateInserted', 
        'dateExpired', 
        'dateDeactivated', 
        'timeDuration', 
        'timeUsed', 
        'status', 
    ];

    protected $hidden = [
        'token',
    ];
}
