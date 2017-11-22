<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    public $table = 'cards';
    public $timestamps = false;

    protected $hidden = ['path'];
/*
	public function user()
	{
		return $this->belongsTo('App\User');
	} */

    public function cardFields()
    {
        return $this->hasMany('App\CardField');
    }

    public function toArray()
    {
        $card = [];

        $card['id']   = $this->id;
        $card['name'] = $this->name;
        $card['path'] = url($this->path);

        $card['fields'] = $this['cardFields'];

        return $card;
    }
}
