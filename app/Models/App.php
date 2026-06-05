<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    public $timestamps = false; 
    protected $table = 'apps';
    protected $primaryKey = 'appID';
    protected $fillable = ['name'];
}
