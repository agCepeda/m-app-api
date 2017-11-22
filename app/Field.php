<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model 
{
	public $table = 'fields';

    protected $hidden = [
        'updated_at', 'created_at'
    ];
}
