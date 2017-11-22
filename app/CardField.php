<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CardField extends Model 
{
	public $table = 'card_field';

    protected $hidden = [
        'updated_at', 'created_at'
    ];

    public function card() 
    {
    	return $this->belongsTo('App\Card');
    }

    public function field() 
    {
    	return $this->belongsTo('App\Field');
    }

    public function toArray()
    {
        $cardField = [];

        $cardField['name']   = $this->field->name;
        $cardField['x']      = $this->x;
        $cardField['y']      = $this->y;
        $cardField['width']  = $this->width;
        $cardField['height'] = $this->height;
        $cardField['font_size'] = $this->font_size;
        $cardField['color'] = $this->color;
        $cardField['align'] = $this->align;
        $cardField['style'] = $this->style;

        return $cardField;
    }
}
