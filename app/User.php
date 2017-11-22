<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Log;

class User extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'id',
        'email',
        'name',
        'last_name',
        'profile_picture',
        'logo',
        'telephone1',
        'telephone2',
        'profession',
        'work_email',
        'company',
        'street',
        'number',
        'neighborhood',
        'zip_code',
        'city',
        'card_id',
        'followers_count',
        'following_count',
        'website',
        'facebook',
        'twitter',
        'instagram',
        'bio',
        'confirmed'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'confirmation_token', 'created_at', 'updated_at', 'profession_id', 'card_id', 'confirmed'
    ];

    public function getLogoAttribute($value)
    {
        return url($value);
    }

    public function sessions()
    {
        return $this->hasMany('App\Session');
    }

    public function profession()
    {
        return $this->belongsTo('App\Profession');
    }

    public function card()
    {
        return $this->belongsTo('App\Card');
    }

    public function notifications()
    {
        return $this->hasMany('App\Notification');
    }

    public function followers()
    {
        return $this->belongsToMany('App\User', 'contacts', 'contact2', 'contact1');
    }

    public function following()
    {
        return $this->belongsToMany('App\User', 'contacts', 'contact1', 'contact2');
    }

/*
    public function toArray()
    {
        $attributes = [];

        $attributes['id']        = $this->id;
        $attributes['email']     = $this->email;
        $attributes['name']      = $this->name;
        $attributes['last_name'] = $this->last_name;
        $attributes['show_name'] = $this->show_name;
        

        $attributes['telephone1'] = $this->telephone1;
        $attributes['telephone2'] = $this->telephone2;
        $attributes['company']    = $this->company;
        $attributes['position']   = $this->position;

        $attributes['street']       = $this->street;
        $attributes['number']       = $this->number;
        $attributes['neighborhood'] = $this->neighborhood;
        $attributes['zip_code']     = $this->zip_code;
        $attributes['city']         = $this->city;

        if (array_key_exists('card', $this->getRelations())) 
            $attributes['card'] = $this->card;

        if (array_key_exists('profession', $this->getRelations())) 
            $attributes['profession'] = $this->profession;
        //$attributes['logo'] = url($this->logo);

        if ($this->review)
            $attributes['review'] = $this->review;

        $attributes['website'] = $this->website;
        $attributes['facebook'] = $this->facebook;
        $attributes['twitter'] = $this->twitter;

        if ($this->contact)
            $attributes['contact']  = $this->contact;
    
        $attributes['click_logo'] = $this->click_logo;
        $attributes['distance']   = $this->distance;

        return $attributes;
    } */

    public function toArray()
    {
        $attributes = [];

        $attributes['id']        = $this->id;
        $attributes['email']     = $this->email;
        $attributes['name']      = $this->name;
        $attributes['last_name'] = $this->last_name;

        if ($this->profile_picture) {
            $attributes['profile_picture'] = url($this->profile_picture);
        } else {
            $attributes['profile_picture'] = url('public/img/default-user.png');
        }

        if ($this->logo) {
            $attributes['logo'] = url($this->logo);
        } else {
            $attributes['logo'] = url('public/img/default-user.png');
        }

        $attributes['telephone1'] = $this->telephone1;
        $attributes['telephone2'] = $this->telephone2;

        $attributes['profession'] = $this->profession;
        
        $attributes['work_email'] = $this->work_email;
        $attributes['company']    = $this->company;

        $attributes['street']    = $this->street;
        $attributes['number']    = $this->number;

        $attributes['neighborhood'] = $this->neighborhood;
        $attributes['zip_code']     = $this->zip_code;
        $attributes['city']         = $this->city;


        if (array_key_exists('card', $this->getRelations())) {
            $attributes['card'] = $this->card;
        }

        $attributes['followers_count'] = $this->followers_count;
        $attributes['following_count'] = $this->following_count;

        $attributes['qr_image'] = url("/user/{$this->id}/qr");

        if (isset($this->distance)) {
            $attributes['distance']   = $this->distance;
        }

        if ($this->contact) {
            $attributes['contact']  = $this->contact;
        }
        
        $attributes['website']  = $this->website;
        $attributes['facebook'] = $this->facebook;
        $attributes['twitter']  = $this->twitter;
        $attributes['instagram']  = $this->instagram;

        $attributes['bio']  = $this->bio;

        return $attributes;
    }
}