<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model 
{
	public $table = 'notifications';

	public function attachment()
	{
		return $this->belongsTo('App\User', 'attachment');
	}
}