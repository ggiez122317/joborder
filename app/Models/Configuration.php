<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    public $timestamps = false; 
    protected $table = 'configurations';
    protected $primaryKey = 'configurationID';
    protected $fillable = [
        'name', 
        'value', 
        'remarks', 
        'isEditable', 
    ];
}
