<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    public $timestamps = false; 
    protected $table = 'UserTypes';
    protected $primaryKey = 'userTypeID';
    protected $fillable = [
        'name', 
        'description', 
        'isEditable', 
    ];
}

