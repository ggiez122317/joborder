<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false; 
    protected $table = 'AuditLogs';
    protected $primaryKey = 'auditLogID';
    protected $fillable = [
        'userID', 
        'username', 
        'ipAddress', 
        'userAgent', 
        'appID', 
        'appModuleActionID', 
        'tableName', 
        'primaryKey', 
        'primaryKeyID', 
        'dateInserted', 
        'dataOld', 
        'dataNew', 
        'remarks', 
    ];
}

