<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model 
{
	public $table = 'contacts';

    protected $hidden = [
        'updated_at', 'created_at'
    ];
}
