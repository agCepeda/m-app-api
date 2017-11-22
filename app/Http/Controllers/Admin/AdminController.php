<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Field;
use App\Card;

class AdminController extends Controller 
{
	public function index(Request $request) 
	{
		return view('admin.card_list');
	}

	public function getFields(Request $request) 
	{
		return Field::all();
	}

	public function uploadCardImage($cardId, Request $request) 
	{
		$card       = Card::findOrFail($cardId);
		$cardImage  = $request->file('card_image');
		$randomName = $this->createRandomString($card->id);
		if($cardImage->move('public/cards', $randomName . '.jpeg'))
			$card->path = 'public/cards/' . $randomName . '.jpeg';
		$card->save();

		return $card->path;
	}
	private function createRandomString($extra) {
		$time    = time();
		$md5Time = md5($time);
		$str     = $time . $md5Time . $extra;

		//$randString = md5($str);

		return $str;
	}
}