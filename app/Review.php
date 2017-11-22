<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model 
{
	public $table = 'reviews';

    protected $hidden = [
        'updated_at', 'created_at'//, 'reviewer_id'
    ];

	public function reviewer()
	{
		return $this->belongsTo('App\User', 'reviewer_id');
	}

	public function user() 
	{
		return $this->belongsTo('App\User');
	}
}
