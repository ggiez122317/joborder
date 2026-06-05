<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLogDetail extends Model
{
    public $timestamps = false; 
    protected $table = 'AuditLogDetails';
    protected $primaryKey = 'auditLogDetailID';
    protected $fillable = [
        'auditLogID', 
        'field', 
        'valueOld', 
        'valueNew', 
    ];
}

