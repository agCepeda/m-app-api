<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profession extends Model 
{
	public $table = 'professions';

    protected $hidden = [
        'updated_at', 'created_at'
    ];

	public function users()
	{
		return $this->hasMany('App\User');
	}
}
