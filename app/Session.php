<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model 
{
	public $table = 'sessions';

    protected $hidden = [
        'updated_at', 'created_at', 'user_id', 'id'
    ];

    protected $fillable = [
    	'user_id',
    	'token',
        'device_token'
    ];

	public function user()
	{
		return $this->belongsTo('App\User');
	}
}
